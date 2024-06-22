<?php
session_start();
// ini_set("display_errors", "1");

// error_reporting(E_ERROR);
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
require("inc/functions_mysqli.php");
require "leadertbl_sales_quota_history.php";

$ownername = '';
$first_deal = 0;
$profit_margin = 0;
$total_profit = 0;
$MGArraysort = array();
$MGArray = array();
$forbillto_sellto = '';
$chkparentonly = '';
$wid = '';
$redirect_url = '';
$transid = '';
$b2b_contact_comp_id = '';
$inv_id_list = '';
$shipfrom_city = '';
$shipfrom_state = '';
$shipfrom_zip = '';
$total_record = 0;
$box_wall = '';
$supplier_id = '';
$vendor_b2b_rescue_id = '';
$urgent_mgArray = array();
$box_type_cnt = 0;
$skey = '';
$sord = '';

$eid = $_COOKIE['b2b_id']; // this is the b2b.employees ID number, not the loop_employees ID number
if ($eid == 0) {
	$eid = 35;
}
if ($eid == 22) {
	$eid = 39;
}
if ($eid == 160) {
	$eid = 39;
}
//$eid=9 of WS 35 - Zac; 39 Jb
$flag_assignto_viewby = 0; //= 1 means in assignto mode and = 0 means in assign to and viewable mode
$sql = "SELECT flag_assignto_viewby FROM employees where employeeID = '" . $eid . "'";
db_b2b();
$result = db_query($sql);
while ($myrowsel = array_shift($result)) {
	$flag_assignto_viewby = $myrowsel["flag_assignto_viewby"];
}

$flag_assignto_viewby_str = "";
if ($flag_assignto_viewby == 0) {
	$flag_assignto_viewby_str = " OR companyInfo.viewable1=" . $eid . " OR companyInfo.viewable2=" . $eid . " OR companyInfo.viewable3=" . $eid . " OR companyInfo.viewable4=" . $eid . " ";
}

$x = "SELECT * from loop_employees WHERE b2b_id = '" . $eid . "'";
db();
$viewres = db_query($x);
$row = array_shift($viewres);
$tmp_view = $row['views'];
if ($_REQUEST["show"] == "search") {
	if (isset($_REQUEST["chktrash"])) {
		if ($_REQUEST["chktrash"] != "on") {
			$tmp_view = str_replace(",31", "", $tmp_view);
		}
	} else {
		$tmp_view = str_replace(",31", "", $tmp_view);
	}
}
//
$viewin = $pieces = explode(",", $tmp_view);
$initials = $row['initials'];
$name = $row['name'];
$commission = $row['commission'];
$dashboard_view = $row['dashboard_view'];
//$viewin = Array (6,47,48,38,3,32,36,51,32,50,43,3,51,24,56,36); //B2B Statuses
$show_number = 250; //number of records to show.
//
$getaccessqry = "SELECT commission_report_access from loop_employees WHERE initials = '" . $initials . "'";
db();
$getaccess = db_query($getaccessqry);
$getaccess_row = array_shift($getaccess);
$commission_access = $getaccess_row["commission_report_access"];

function showolderthan3months_all(): void
{

	if ($_REQUEST["so"] == "A") {
		$so = "D";
	} else {
		$so = "A";
	}

?>
<script>
function show_trash() {
    var chklist = document.getElementById('chktrash_three').checked;
    document.location = "dashboardnew.php?show=olderthan3months&chklist=" + chklist;
}
</script>


Include Trash and Active/Inactive:<input type="checkbox" value="true" name="chktrash_three" id="chktrash_three" <?php if ($_REQUEST["chklist"] == "true") {
																														echo "checked";
																													} else {
																														echo "";
																													} ?>>
<input type='button' value="Search" onclick="show_trash()">
<div><i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of the report, before
        using the sort option.</i></div>
<table width="1300" border="0" cellspacing="1" cellpadding="1">
    <tr align="center">
        <td colspan="12" bgcolor="#FFCCCC">
            <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
                <b><?php echo "Older Than Three Months" ?></b>
            </font>
        </td>
    </tr>
    <tr>

        <td width="8%" bgcolor="#D9F2FF">
            <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=age&so=" . $so); ?>">AGE</a>
            </font>
        </td>
        <td width="10%" bgcolor="#D9F2FF">
            <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=accstatus&so=" . $so); ?>">Account
                    Status</a></font>
        </td>
        <td width="10%" bgcolor="#D9F2FF" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=contact&so=" . $so); ?>">CONTACT</a>
            </font>
        </td>
        <td width="11%" bgcolor="#D9F2FF">
            <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=comp&so=" . $so); ?>">COMPANY
                    NAME</a></font>
        </td>
        <td width="3%" bgcolor="#D9F2FF">
            <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=phone&so=" . $so); ?>">PHONE</a>
            </font>
        </td>
        <td bgcolor="#D9F2FF" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=city&so=" . $so); ?>">CITY</a>
            </font>
        </td>
        <td bgcolor="#D9F2FF" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=state&so=" . $so); ?>">STATE</a>
            </font>
        </td>
        <td bgcolor="#D9F2FF" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=zip&so=" . $so); ?>">ZIP</a>
            </font>
        </td>
        <td bgcolor="#D9F2FF" align="center">
            <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=nextstep&so=" . $so); ?>">Next
                    Step</a></font>
        </td>
        <td bgcolor="#D9F2FF" align="center">
            <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=lastcom&so=" . $so); ?>">Last
                    Communication</a></font>
        </td>
        <td bgcolor="#D9F2FF" align="center">
            <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                    href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?show=olderthan3months&sk=nextcom&so=" . $so); ?>">Next
                    Communication</a></font>
        </td>
    </tr>

    <?php
		if (!isset($_REQUEST["sk"])) {
			$MGArray = array();
			$dt = date('Y-m-d', strtotime('-90 days'));


			$eid_tmp = $_COOKIE['b2b_id'];
			if ($eid_tmp == 0) {
				$eid_tmp = 35;
			}

			if ($_REQUEST["chklist"] == "true") {
				$x = "Select ";
				$x .= " status.name as statusname, companyInfo.last_date AS LD,companyInfo.status ,companyInfo.ID AS I,";
				$x .= " companyInfo.loopid AS LID, companyInfo.contact AS C, companyInfo.dateCreated AS D,";
				$x .= " companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,";
				$x .= " companyInfo.city AS CI, companyInfo.state AS ST, companyInfo.zip AS ZI,";
				$x .= " companyInfo.next_step AS NS, companyInfo.next_date AS ND";
				$x .= " from companyInfo left join status on companyInfo.status = status.id ";
				$x .= " Where order by statusname, companyInfo.company ";
			} else {
				$x = "Select ";
				$x .= " status.name as statusname, companyInfo.last_date AS LD,companyInfo.status ,companyInfo.ID AS I,";
				$x .= " companyInfo.loopid AS LID, companyInfo.contact AS C, companyInfo.dateCreated AS D,";
				$x .= " companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,";
				$x .= " companyInfo.city AS CI, companyInfo.state AS ST, companyInfo.zip AS ZI,";
				$x .= " companyInfo.next_step AS NS, companyInfo.next_date AS ND";
				$x .= " from companyInfo left join status on companyInfo.status = status.id ";
				$x .= " Where companyInfo.status not in(24,31,49) and companyInfo.active=1 order by statusname, companyInfo.company ";
			}

			//echo $x."<br>";
			//exit;
			db_b2b();
			$dt_view_res_x = db_query($x);
			//echo $row['ID']."---".$row['messageDate']."---".$dt."<br><br>" ;

			while ($data = array_shift($dt_view_res_x)) {
				$qry = "select max(str_to_date(messageDate, '%m/%d/%Y')) as msg_dt from CRM where companyID=" . $data['I'];
				//echo $qry."<br>";
				db_b2b();
				$dt_view_res = db_query($qry);
				while ($myrow = array_shift($dt_view_res)) {
					//echo $myrow['msg_dt']."<br>";
					if (($myrow['msg_dt']) <= ($dt)) {

						$MGArray[] = array('statusname' => $data["statusname"], 'age' => date_diff_new($myrow["msg_dt"], "NOW"), 'contact' => $data["C"], 'id' => $data["I"], 'LID' => $data["LID"], 'CO' => $data["CO"], 'PH' => $data["PH"], 'CI' => $data["CI"], 'ST' => $data["ST"], 'ZI' => $data["ZI"], 'NS' => $data["NS"], 'LD' => $data["LD"], 'msg_dt' => date('Y-m-d', strtotime($myrow["msg_dt"])), 'ND' => date('Y-m-d', strtotime($data["ND"])));
		?>
    <tr valign="middle">
        <td width="5%" bgcolor="#E4E4E4">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                <?php echo date_diff_new($myrow["msg_dt"], "NOW"); ?> Days</font>
        </td>
        <td width="10%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["statusname"] ?></font>
        </td>
        <td width="10%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["C"] ?></font>
        </td>
        <td width="11%" bgcolor="#E4E4E4"><a
                href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $data["I"] ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["CO"] ?><?php if ($data["LID"] > 0) echo "</b>"; ?>
                </font>
            </a></td>
        <td width="3%" bgcolor="#E4E4E4">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["PH"] ?></font>
        </td>
        <td width="10%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["CI"] ?></font>
        </td>
        <td width="5%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["ST"] ?></font>
        </td>
        <td width="5%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["ZI"] ?></font>
        </td>
        <td width="15%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["NS"] ?></font>
        </td>
        <td width="10%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                <?php if ($data["LD"] != "") echo date('Y-m-d', strtotime($myrow["msg_dt"])); ?>
        <td width="10%" <?php if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00"
            <?php } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#FF0000" <?php } else { ?>
            bgcolor="#E4E4E4" <?php } ?> align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                <?php if ($data["ND"] != "") echo date('Y-m-d', strtotime($data["ND"])); ?></font>
        </td>
    </tr>
    <?php
						$_SESSION['sortarrayn'] = $MGArray;
					}
				}
			}
		} else {
			$MGArray = $_SESSION['sortarrayn'];

			$sort_order_arrtxt = "SORT_ASC";
			if ($_REQUEST["so"] != "") {
				if ($_REQUEST["so"] == "A") {
					$sort_order_arrtxt = "SORT_ASC";
				} else {
					$sort_order_arrtxt = "SORT_DESC";
				}
			} else {
				$sort_order_arrtxt = "SORT_DESC";
			}

			if ($_REQUEST["sk"] == "age") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['age'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
				}
			}

			if ($_REQUEST["sk"] == "accstatus") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['statusname'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}
			if ($_REQUEST["sk"] == "contact") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['contact'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}
			if ($_REQUEST["sk"] == "comp") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['CO'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}
			if ($_REQUEST["sk"] == "phone") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['PH'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}
			if ($_REQUEST["sk"] == "city") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['CI'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}
			if ($_REQUEST["sk"] == "state") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['ST'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}
			if ($_REQUEST["sk"] == "zip") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['ZI'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}
			if ($_REQUEST["sk"] == "nextstep") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['NS'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}
			if ($_REQUEST["sk"] == "lastcom") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['msg_dt'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}
			if ($_REQUEST["sk"] == "nextcom") {
				$MGArraysort = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['ND'];
				}
				if ($sort_order_arrtxt == "SORT_ASC") {
					array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
				} else {
					array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
				}
			}

			foreach ($MGArray as $MGArraytmp2) { ?>
    <tr valign="middle">
        <td width="5%" bgcolor="#E4E4E4">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["age"]; ?> Days
            </font>
        </td>
        <td width="10%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["statusname"] ?>
            </font>
        </td>
        <td width="10%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["contact"] ?>
            </font>
        </td>
        <td width="21%" bgcolor="#E4E4E4"><a
                href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $MGArraytmp2["id"] ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["CO"] ?><?php if ($MGArraytmp2["LID"] > 0) echo "</b>"; ?>
                </font>
            </a></td>
        <td width="3%" bgcolor="#E4E4E4">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["PH"] ?></font>
        </td>
        <td width="10%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["CI"] ?></font>
        </td>
        <td width="5%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["ST"] ?></font>
        </td>
        <td width="5%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["ZI"] ?></font>
        </td>
        <td width="15%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["NS"] ?></font>
        </td>
        <td width="10%" bgcolor="#E4E4E4" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                <?php if ($MGArraytmp2["LD"] != "") echo date('m/d/Y', strtotime($MGArraytmp2["msg_dt"])); ?>
        <td width="10%" <?php if ($MGArraytmp2["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00"
            <?php } elseif ($MGArraytmp2["ND"] < date('Y-m-d') && $MGArraytmp2["ND"] != "") { ?> bgcolor="#FF0000"
            <?php } else { ?> bgcolor="#E4E4E4" <?php } ?> align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                <?php if ($MGArraytmp2["ND"] != "") echo date('m/d/Y', strtotime($MGArraytmp2["ND"])); ?></font>
        </td>
    </tr>
    <?php }
		}

		?>
</table>
<div><i>
        <font color="red">"END OF REPORT"</font>
    </i></div>
</form>
<?php
}


function showopenquotes_all(): void
{
?>
<form method="post" action="updateQuoteStatus2.php">
    <table>
        <tr>
            <td class="style24" colspan=18 style="height: 16px" align="middle"><strong>OPEN QUOTES</strong></td>
        </tr>
        <tr>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Date</strong></td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Company</strong></td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Rep</strong></td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Type</strong></td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Amount</strong></td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Status</strong></td>
        </tr>
        <?php

			$quotes_archive_date = "";
			$query = "SELECT variablevalue FROM tblvariable where variablename = 'quotes_archive_date'";
			db();
			$dt_view_res3 = db_query($query);
			while ($objQuote = array_shift($dt_view_res3)) {
				$quotes_archive_date = $objQuote["variablevalue"];
			}


			$dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE (qstatus = 1 OR qstatus = 0 OR qstatus = 10) AND (quoteType = 'Quote' OR quoteType = 'Quote Select') ORDER BY quoteDate DESC";
			//echo $dt_view_qry;
			db_b2b();
			$dt_view_res = db_query($dt_view_qry);
			while ($dt_view_row = array_shift($dt_view_res)) {
			?>
        <tr>

            <td bgColor="#e4e4e4" class="style12">
                <?php echo timestamp_to_date($dt_view_row["quoteDate"]); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["companyID"]; ?>"><?php echo getnickname($dt_view_row["company"], $dt_view_row["companyID"]); ?></a>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $dt_view_row["E"]; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
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
                        href="https://loops.usedcardboardboxes.com/quotes/<?php echo $dt_view_row["filename"]; ?>">
                        <?php
								}
							} elseif ($dt_view_row["quoteType"] == "Quote") {

									?>
                        <a href="https://loops.usedcardboardboxes.com/fullquote.php?ID=<?php echo $dt_view_row["I"] ?>">
                            <?php } elseif ($dt_view_row["quoteType"] == "Quote Select") {

									?>
                            <a
                                href="http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=<?php echo $dt_view_row["I"] ?>">
                                <?php } ?> <?php echo $dt_view_row["quoteType"]; ?></a>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo number_format($dt_view_row["quote_total"], 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">

                <input type="hidden" name="quote_id[]" value="<?php echo $dt_view_row["I"] ?>">
                <select size="1" name="quote_status[]">
                    <?php
							$box = 0;

							$boxSql = "Select * from quote_status Where status=1";
							db_b2b();
							$dt_view_res4 = db_query($boxSql);
							while ($objQStatus = array_shift($dt_view_res4)) {

								if ($objQStatus["qid"] == $dt_view_row["qstatus"])
									$strSelected = " selected ";
								else
									$strSelected = "";

							?>

                    <option value="<?php echo $objQStatus["qid"] ?>" <?php echo $strSelected ?>>
                        <?php echo $objQStatus["status_name"] ?></option>
                    <?php
							}
							?>
                </select>
            </td>
        </tr>
        <?php
			}	//while loop
			?>
        <tr>
            <td class="style24" colspan=18 style="height: 16px" align="middle"> <input type="submit" value="Update"
                    name="B1"></td>
        </tr>
    </table>
</form>
<?php
}


function showStatusesDashboard_all(array $arrVal, int $eid, int $limit, string $period, int $water_flg = 0, string $rep_name = '')
{

	$chkparentonly = "";

?>
<form name="frmstatusdashboard" id="frmstatusdashboard" method="post" action="">
    <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
    </div>
    <?php
		//echo "LIMIT" . $limit;
		if ($_REQUEST["so"] == "A") {
			$so = "D";
		} else {
			$so = "A";
		}

		$skey = "";
		$sord = "";
		if ($_REQUEST["sk"] != "") {
			if ($eid > 0) {
				$tmp_sortorder = "";
				if ($_REQUEST["sk"] == "dt") {
					$tmp_sortorder = "companyInfo.dateCreated";
				} elseif ($_REQUEST["sk"] == "age") {
					$tmp_sortorder = "companyInfo.dateCreated";
				} elseif ($_REQUEST["sk"] == "cname") {
					$tmp_sortorder = "companyInfo.company";
				} elseif ($_REQUEST["sk"] == "qty") {
					$tmp_sortorder = "companyInfo.company";
				} elseif ($_REQUEST["sk"] == "nname") {
					$tmp_sortorder = "companyInfo.nickname";
				} elseif ($_REQUEST["sk"] == "nd") {
					$tmp_sortorder = "companyInfo.next_date";
				} elseif ($_REQUEST["sk"] == "ns") {
					$tmp_sortorder = "companyInfo.next_step";
				} elseif ($_REQUEST["sk"] == "ei") {
					$tmp_sortorder = "employees.initials";
				} elseif ($_REQUEST["sk"] == "lc") {
					$tmp_sortorder = "companyInfo.company";
				} else {
					$tmp_sortorder = "companyInfo." . $_REQUEST["sk"];
				}

				if ($so == "A") {
					$tmp_sort = "D";
				} else {
					$tmp_sort = "A";
				}
				$sql_qry = "update employees set sort_fieldname = '" . $tmp_sortorder . "', sort_order='" . $tmp_sort . "' where employeeID = " . $eid;
				db_b2b();
				db_query($sql_qry);
			}

			if ($_REQUEST["sk"] == "dt") {
				$skey = " ORDER BY companyInfo.dateCreated";
			} elseif ($_REQUEST["sk"] == "age") {
				$skey = " ORDER BY companyInfo.dateCreated";
			} elseif ($_REQUEST["sk"] == "contact") {
				$skey = " ORDER BY companyInfo.contact";
			} elseif ($_REQUEST["sk"] == "cname") {
				$skey = " ORDER BY companyInfo.company";
			} elseif ($_REQUEST["sk"] == "nname") {
				$skey = " ORDER BY companyInfo.nickname";
			} elseif ($_REQUEST["sk"] == "city") {
				$skey = " ORDER BY companyInfo.city";
			} elseif ($_REQUEST["sk"] == "state") {
				$skey = " ORDER BY companyInfo.state";
			} elseif ($_REQUEST["sk"] == "zip") {
				$skey = " ORDER BY companyInfo.zip";
			} elseif ($_REQUEST["sk"] == "nd") {
				$skey = " ORDER BY companyInfo.next_date";
			} elseif ($_REQUEST["sk"] == "ns") {
				$skey = " ORDER BY companyInfo.next_step";
			} elseif ($_REQUEST["sk"] == "ei") {
				$skey = " ORDER BY employees.initials";
			} elseif ($_REQUEST["sk"] == "lc") {
				$skey = " ORDER BY companyInfo.last_contact_date";
			} else {
				$skey = "";
			}

			if ($_REQUEST["so"] != "") {
				if ($_REQUEST["so"] == "A") {
					$sord = " ASC";
				} else {
					$sord = " DESC";
				}
			} else {
				$sord = " DESC";
			}
		} else {
			if ($eid > 0) {
				$sql_qry = "Select sort_fieldname, sort_order from employees where employeeID = " . $eid .  "";
				db_b2b();
				$dt_view_res = db_query($sql_qry);
				while ($row = array_shift($dt_view_res)) {
					if ($row["sort_fieldname"] != "") {
						if ($row["sort_order"] == "A") {
							$sord = " ASC";
						} else {
							$sord = " DESC";
						}
						$skey = " ORDER BY " . $row["sort_fieldname"];
					} else {
						$skey = " ORDER BY companyInfo.dateCreated ";
						$sord = " DESC";
					}
				}
			} else {
				$skey = " ORDER BY companyInfo.dateCreated ";
				$sord = " DESC";
			}
		}

		$flag_assignto_viewby = 0; //= 1 means in assignto mode and = 0 means in assign to and viewable mode
		$flag_assignto_viewby_str = "";

		$tmpdisplay_flg = "n";
		if ($period == "old") {
			//$dt_view_qry = "SELECT * FROM status WHERE id not IN ( 24, 31, 43, 44, 49) ORDER BY sort_order";
			$dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($arrVal) .  ") ORDER BY sort_order";
		} else {
			$dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($arrVal) .  " ) ORDER BY sort_order";
		}

		$pos_srch = stripos($dt_view_qry, "0,");
		if ($pos_srch === false) {
		} else {
			$dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($arrVal) .  " ) union SELECT 0, 'Unassign', 'Unassign', 27, 0, '' FROM status ORDER BY sort_order";
		}

		//echo $dt_view_qry . "<br>";
		$ctrl_cnt = 0;
		db_b2b();
		$dt_view_res = db_query($dt_view_qry);
		while ($row = array_shift($dt_view_res)) {

			if ($row["id"] != 58) {
				if ($water_flg == 0) {
					$x = "Select companyInfo.shipCity , companyInfo.shipState, companyInfo.shipZip, companyInfo.id AS I,  companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.status =" . $row["id"];
				} else {
					$x = "Select companyInfo.shipCity , companyInfo.shipState, companyInfo.shipZip, companyInfo.id AS I,  companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.ucbzw_account_owner = employees.employeeID Where companyInfo.ucbzw_account_status =" . $row["id"];
				}
			} else {
				$x = "Select companyInfo.shipCity , companyInfo.shipState, companyInfo.shipZip, companyInfo.id AS I,  companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.special_ops = 1 ";
			}

			//if ($_REQUEST["show"] != "search" AND $row["id"] != 58) {
			if ($_REQUEST["show"] == "unassigned") {
				if ($water_flg == 0) {
					$x = "Select companyInfo.shipCity , companyInfo.shipState, companyInfo.shipZip, companyInfo.id AS I, companyInfo.loopid AS LID,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID  Where companyInfo.status =" . $row["id"] . " AND  companyInfo.assignedto = 0 AND companyInfo.company != 'v' ";
				} else {
					$x = "Select companyInfo.shipCity , companyInfo.shipState, companyInfo.shipZip, companyInfo.id AS I, companyInfo.loopid AS LID,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID  Where companyInfo.ucbzw_account_status =" . $row["id"] . " AND  companyInfo.ucbzw_account_owner = 0 AND companyInfo.company != 'v' ";
				}
			}

			if ($rep_name == 'external_leads_Prospects') {
				$x = $x . " and internal_external = 'External'";
			}
			if ($rep_name == 'internal_leads_Prospects') {
				$x = $x . " and internal_external = 'Internal'";
			}

			if ($_REQUEST["gc"] == 1) {
				$x = $x . " AND companyInfo.haveNeed LIKE 'Need Boxes'";
			}
			if ($_REQUEST["gc"] == 2) {
				$x = $x . " AND companyInfo.haveNeed LIKE 'Have Boxes'";
			}

			if ($period != "all") {
				if ($period == "today") {
					$x = $x . " AND companyInfo.next_date = CURDATE() ";
				}
				if ($period == "upcoming") {
					$x = $x . " AND (companyInfo.next_date > '" . date('Y-m-d') . "' and companyInfo.next_date <= '" . date('Y-m-d', strtotime("+7 days")) . "')";
				}
				if ($period == "lastweek") {
					$x = $x . " AND (companyInfo.next_date <= '" . date('Y-m-d') . "' and companyInfo.next_date >= '" . date('Y-m-d', strtotime("-7 days")) . "')";
				}
				if ($period == "old") {
					$x = $x . " AND companyInfo.next_date < CURDATE() AND companyInfo.next_date > '1900-01-01'";
				}
				if ($period == "none") {
					$x = $x . " AND companyInfo.next_date IS NULL";
				}
			}


			$search_res_sales_str = " ";
			$search_res_sales_loop_str = " ";
			if ($_REQUEST["search_res_sales"] == "Sales") {
				$search_res_sales_str = " and haveNeed = 'Need Boxes' ";
				$search_res_sales_loop_str = " and bs_status = 'Buyer' ";
			}
			if ($_REQUEST["search_res_sales"] == "Purchasing") {
				$search_res_sales_str = " and haveNeed = 'Have Boxes' ";
				$search_res_sales_loop_str = " and bs_status = 'Seller' ";
			}

			$chkparentonly = " ";
			if (isset($_REQUEST["chkparentonly"])) {
				if ($_REQUEST["chkparentonly"] == "on") {
					$chkparentonly = " and parent_child = 'Parent' ";
				} else {
					$chkparentonly = " ";
				}
			}

			if ($_REQUEST["show"] == "search") {
				if ($_REQUEST["andor"] == "exactmatch") {
					$arrFields = array("contact", "nickname", "contact2", "company", "industry", "address", "address2", "city", "state", "zip", "country", "phone", "fax", "email", "website", "billing_first_name", "billing_last_name", "billing_address1", "billing_address2", "billing_city", "billing_state", "billing_zip", "help", "shipContact", "shipTitle", "shipAddress", "shipAddress2", "shipCity", "shipState", "shipZip", "shipPhone", "comp_abbrv");
					$i = 1;
					$x = $x . " AND ( ";
					foreach ($arrFields as $nm) {
						if ($i == 1) {;
						} else {
							$x = $x . " OR ";
						}
						$x = $x . " companyInfo." . $nm . " = '" . str_replace("'", "\'", $_REQUEST["searchterm"]) . "'";
						$i++;
					}
					$x = $x . " ) ";
				} else {
					$arrFields = array("contact", "contactTitle", "contact2", "contactTitle2", "company", "industry", "address", "address2", "city", "state", "zip", "country", "phone", "fax", "email", "website", "order_no", "choose", "ccheck", "billing_first_name", "billing_last_name", "billing_address1", "billing_address2", "billing_city", "billing_state", "billing_zip", "billing_question", "information", "help", "experience", "mail_lists", "card_owner", "shipContact", "shipTitle", "shipAddress", "shipAddress2", "shipCity", "shipState", "shipZip", "shipPhone", "status", "status2", "haveNeed", "notes", "dateCreated", "dateLastAccessed", "poNumber", "terms", "rep", "TBD", "shipDate", "via", "quoteNote", "howHear", "pickupDay", "pickupWeek", "lastPickup", "req_type", "vendor", "int_notes", "green_initiative", "nickname", "next_step", "comp_abbrv");
					$st = explode(' ', $_REQUEST["searchterm"]);

					$x = $x . " AND ( ";
					foreach ($st as $sti) {
						$i = 1;
						$x = $x . " ( ";
						foreach ($arrFields as $nm) {

							if ($i == 1) {;
							} else {
								$x = $x . " OR ";
							}
							$x = $x . " companyInfo." . $nm . " LIKE '%" . str_replace("'", "\'", $sti) . "%'";
							$i++;
						}
						$x = $x . " ) " . $_REQUEST["andor"] . " ";
					}

					if ($_REQUEST["andor"] == "AND") {
						$x = $x . " TRUE ) ";
					} else {
						$x = $x . " FALSE ) ";
					}

					if ($_REQUEST["state"] != "ALL")
						$x = $x . " AND companyInfo.state LIKE '" . $_REQUEST["state"] . "' ";
				}
				$x = $x . $search_res_sales_str . $chkparentonly;
			}

			$x = $x . " GROUP BY companyInfo.id " . $skey . $sord . " ";
			//echo "<br/>" . $x . "<br/><br/>";
			if ($limit > 0) {
				$xL = $x . " LIMIT 0, " . $limit;
				db_b2b();
				$data_res = db_query($xL);
				$data_res_No_Limit = db_query($x);
				$show = "" . $limit;
			} else {
				db_b2b();
				$data_res = db_query($x);
				$data_res_No_Limit = db_query($x);
				$show = "All";
			}


			if (tep_db_num_rows($data_res_No_Limit) > 0) {

				if ($tmpdisplay_flg == "n") {
					$tmpdisplay_flg = "y";
					if ($flag_assignto_viewby == 1) {
						echo "Note: Records displayed are 'Assign to you'.<br>";
					} else {
						echo "Note: Records displayed are 'Assign to you and Viewable by you'.<br>";
					}
				}

		?>
    <table class="past_due tbl_data<?php echo $row['id']; ?>" width="1300" border="0" cellspacing="1" cellpadding="1">
        <tr align="center">
            <td colspan="15" bgcolor="#FFCCCC">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
                    <b><?php echo $row["name"] . " - Total Records: " . tep_db_num_rows($data_res_No_Limit) . " - Showing: "; ?>
                        <?php if ($limit > 0) {
										if ($limit > tep_db_num_rows($data_res_No_Limit)) {
											echo tep_db_num_rows($data_res_No_Limit);
										} else {
											echo $limit;
										} ?> <a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "&limit=all"); ?>">Show
                            All</a>
                        <?php } else { ?>All<?php } ?>
                    </b>
                </font>
            </td>
        </tr>
        <?php
					$sorturl = htmlentities($_SERVER['PHP_SELF'] . "?show=" . $_REQUEST['show'] . "&period=" . $_REQUEST['period'] . "&statusid=" . $_REQUEST['statusid'] . "&searchterm=" . $_REQUEST['searchterm'] . "&andor=" . $_REQUEST['andor'] . "&state=" . $_REQUEST['state']);
					?>
        <?php if (1 == 1 or $limit == 100000) { ?>
        <tr>
            <td width="5%" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    DATE
                    <a href="<?php echo $sorturl; ?>&sk=dt&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;">
                    </a>
                    <a href="<?php echo $sorturl; ?>&sk=dt&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td width="5%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">AGE
                    <a href="<?php echo $sorturl; ?>&sk=age&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=age&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td width="10%" bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">CONTACT
                    <a href="<?php echo $sorturl; ?>&sk=contact&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=contact&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td width="21%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    COMPANY NAME
                    <a href="<?php echo $sorturl; ?>&sk=cname&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=cname&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <!-- <td bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nname&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">NICKNAME</a></font></td> -->
            <td width="8%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    CITY
                    <a href="<?php echo $sorturl; ?>&sk=city&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=city&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    STATE
                    <a href="<?php echo $sorturl; ?>&sk=state&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=state&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    ZIP
                    <a href="<?php echo $sorturl; ?>&sk=zip&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=zip&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    Next Step
                    <a href="<?php echo $sorturl; ?>&sk=ns&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=ns&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    Last<br>Communication
                    <a href="<?php echo $sorturl; ?>&sk=lc&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=lc&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    Next Communication
                    <a href="<?php echo $sorturl; ?>&sk=nd&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=nd&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    Assigned To
                    <a href="<?php echo $sorturl; ?>&sk=ei&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=ei&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Update</font>
            </td>
        </tr>
        <?php
						$forbillto_sellto = "";
						while ($data = array_shift($data_res)) {
							$forbillto_sellto = $forbillto_sellto  . $data["I"] . ", ";

							if ($data["NN"] != "") {
								$nickname = $data["NN"];
							} else {
								$tmppos_1 = strpos($data["CO"], "-");
								if ($tmppos_1 != false) {
									$nickname = $data["CO"];
								} else {
									if ($data["shipCity"] <> "" || $data["shipState"] <> "") {
										$nickname = $data["CO"] . " - " . $data["shipCity"] . ", " . $data["shipState"];
									} else {
										$nickname = $data["CO"];
									}
								}
							}

						?>
        <tr valign="middle" id="tbl_div<?php echo $ctrl_cnt; ?>">
            <td width="5%" bgcolor="#E4E4E4">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo  timestamp_to_datetime($data["D"]);
																																					?></font>
            </td>
            <td width="5%" bgcolor="#E4E4E4">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo date_diff_new($data["D"], "NOW");
																																					?> Days</font>
            </td>
            <td width="10%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["C"] ?></font>
            </td>
            <td width="21%" bgcolor="#E4E4E4"><a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $data["I"] ?>">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $nickname; ?><?php if ($data["LID"] > 0) echo "</b>"; ?>
                    </font>
                </a></td>
            <!-- <td bgcolor="#E4E4E4"><a href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $data["I"] ?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["NN"] ?></font></a></td> -->
            <td width="3%" bgcolor="#E4E4E4">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["PH"] ?></font>
            </td>
            <td width="10%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["shipCity"] ?></font>
            </td>
            <td width="5%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["shipState"] ?></font>
            </td>
            <td width="5%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["shipZip"] ?></font>
            </td>
            <td width="15%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><textarea
                        id="note<?php echo $ctrl_cnt; ?>"><?php echo $data["NS"] ?></textarea></font>
            </td>

            <td width="10%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php if ($data["LD"] != "") echo date('m/d/Y', strtotime($data["LD"])); ?>
            <td width="10%" <?php if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00"
                <?php } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#FF0000"
                <?php } else { ?> bgcolor="#E4E4E4" <?php } ?> align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <input type="text" size="8" name="txt_next_step_dt" id="txt_next_step_dt<?php echo $ctrl_cnt; ?>"
                        value="<?php if ($data["LID"] > 0) ?><?php if ($data["ND"] != "") echo date('m/d/Y', strtotime($data["ND"])); ?>">
                    <a href="#"
                        onclick="cal2xx.select(document.frmstatusdashboard.txt_next_step_dt<?php echo $ctrl_cnt; ?>,'dtanchor1xx<?php echo $ctrl_cnt; ?>','yyyy-MM-dd'); return false;"
                        name="dtanchor1xx" id="dtanchor1xx<?php echo $ctrl_cnt; ?>"><img border="0"
                            src="images/calendar.jpg"></a>
                </font>
            </td>

            <td bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["EI"] ?></font>
            </td>

            <td bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <input type="button" name="btnupdate" id="btnupdate" value="Update"
                        onclick="update_details(<?php echo $ctrl_cnt; ?>, <?php echo $data["I"] ?>)">
                </font>
            </td>

        </tr>

        <?php
							$ctrl_cnt = $ctrl_cnt + 1;
						} // of the inactive or reactive if
						ob_flush();
					}
					echo "</table><p>";

					if (tep_db_num_rows($data_res_No_Limit) > 22) {
						echo "<a href='#' class='load_more' id='load_more" . $row['id'] . "'>Load more</a></p></p>";
					}

					?>
        <script>
        $(document).ready(function() {
            $('.tbl_data<?php echo $row['id']; ?> tr:nth-child(n+1):nth-child(-n+22)').addClass('active');

            $('#load_more<?php echo $row['id']; ?>').on('click', function(e) {
                e.preventDefault();
                var $rows = $('.tbl_data<?php echo $row['id']; ?> tr');
                var lastActiveIndex = $rows.filter('.active:last').index();
                $rows.filter(':lt(' + (lastActiveIndex + 22) + ')').addClass('active');
                if ($rows.length <= (lastActiveIndex + 22)) $('#load_more<?php echo $row['id']; ?>')
                    .hide();
            });
        });
        </script>
        <?php

			}
		}

		if ($_REQUEST["show"] == "search") {
			//for Bill to 
			$arrFields_billto = array("title", "name", "address", "address2", "city", "state", "zipcode", "mainphone", "directphone", "cellphone", "email", "fax");

			//$qryfor_billto = " Select dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_contact_date, next_date from companyInfo where ID in (Select companyid from b2bbillto where ";
			$qryfor_billto = " Select companyid from b2bbillto where ";

			$i = 1;
			$qryfor_billto = $qryfor_billto . " ( ";
			foreach ($arrFields_billto as $nm) {

				if ($i == 1) {;
				} else {
					$qryfor_billto = $qryfor_billto . " OR ";
				}
				$qryfor_billto = $qryfor_billto . " b2bbillto." . $nm . " LIKE '%" . $_REQUEST["searchterm"] . "%'";
				$i++;
			}


			if ($_REQUEST["andor"] == "AND") {
				//$qryfor_billto = $qryfor_billto . " TRUE  ";
			} else {
				//$qryfor_billto = $qryfor_billto . " FALSE  ";
			}

			if ($_REQUEST["state"] != "ALL") {
				$qryfor_billto = $qryfor_billto . " ) and (b2bbillto.state LIKE '" . $_REQUEST["state"] . "') ";
			}
			if ($_REQUEST["state"] == "ALL") {
				$qryfor_billto = $qryfor_billto . " )";
			}

			//echo $qryfor_billto;	

			$qryfor_billto_str = "";
			db_b2b();
			$result_tmp = db_query($qryfor_billto);
			while ($myrowsel_tmp = array_shift($result_tmp)) {
				$qryfor_billto_str = $qryfor_billto_str . $myrowsel_tmp["companyid"] . ", ";
			}
			if ($qryfor_billto_str != "") {
				$qryfor_billto_str = substr($qryfor_billto_str, 0, strlen($qryfor_billto_str) - 2);
			} else {
				$qryfor_billto_str = '0';
			}

			if (isset($forbillto_sellto) != "") {
				$forbillto_sellto = substr($forbillto_sellto, 0, strlen($forbillto_sellto) - 2);
			} else {
				$forbillto_sellto = '';
			}

			//for Sell to 
			$arrFields_sellto = array("title", "name", "address", "address2", "city", "state", "zipcode", "mainphone", "directphone", "cellphone", "email", "fax");

			//$qryfor_sellto = " Select dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_contact_date, next_date from companyInfo where ID in (Select companyid from b2bsellto where ";
			$qryfor_sellto = " Select companyid from b2bsellto where ";

			$i = 1;
			if ($_REQUEST["andor"] == "exactmatch") {
				$qryfor_sellto = $qryfor_sellto . " ( ";

				foreach ($arrFields_sellto as $nm) {

					if ($i == 1) {;
					} else {
						$qryfor_sellto = $qryfor_sellto . " OR ";
					}
					$qryfor_sellto = $qryfor_sellto . " b2bsellto." . $nm . " LIKE '%" . $_REQUEST["searchterm"] . "%'";

					$i++;
				}

				$qryfor_sellto = $qryfor_sellto . " ) ";
			} else {
				$st = explode(' ', $_REQUEST["searchterm"]);

				$data_tmp_found = "n";
				foreach ($st as $sti) {
					$i = 1;
					$qryfor_sellto = $qryfor_sellto . " ( ";
					foreach ($arrFields_sellto as $nm) {
						if ($i == 1) {;
						} else {
							$qryfor_sellto = $qryfor_sellto . " OR ";
						}
						$data_tmp_found = "y";
						$qryfor_sellto = $qryfor_sellto . " b2bsellto." . $nm . " LIKE '%" . $sti . "%'";
						$i++;
					}

					if ($data_tmp_found == "y") {
						$qryfor_sellto = $qryfor_sellto . ") Or ";
					}
				}
				$qryfor_sellto = substr($qryfor_sellto, 0, strlen($qryfor_sellto) - 3);
			}

			if ($_REQUEST["andor"] == "AND") {
				//$qryfor_sellto = $qryfor_sellto . " TRUE  ";
			} else {
				//$qryfor_sellto = $qryfor_sellto . " FALSE  ";
			}

			if ($_REQUEST["state"] != "ALL")
				$qryfor_sellto = $qryfor_sellto . " and (b2bsellto.state LIKE '" . $_REQUEST["state"] . "') ";

			if ($_REQUEST["state"] == "ALL")
				$qryfor_sellto = $qryfor_sellto . " ";

			$qryfor_sellto_str = "";
			//echo $qryfor_sellto . "<br>";
			db_b2b();
			$result_tmp = db_query($qryfor_sellto);
			while ($myrowsel_tmp = array_shift($result_tmp)) {
				$qryfor_sellto_str = $qryfor_sellto_str . $myrowsel_tmp["companyid"] . ", ";
			}

			if ($qryfor_sellto_str != "") {
				$qryfor_sellto_str = substr($qryfor_sellto_str, 0, strlen($qryfor_sellto_str) - 2);
			} else {
				$qryfor_sellto_str = '0';
			}


			//Search the details in the Bill to 
			//$qryfor_billto = "Select dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_contact_date, next_date from companyInfo where ID in (" . $qryfor_billto_str . ") or not ( $forbillto_sellto GROUP BY companyInfo.id " . $skey . $sord . " ";
			if ($forbillto_sellto != "") {

				$qryfor_billto = "Select employees.initials, shipCity, shipState, dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_contact_date, next_date from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID where ID in (" . $qryfor_billto_str . ") and ID not in ( $forbillto_sellto ) $chkparentonly GROUP BY companyInfo.id "; //" . $skey . $sord . " 
			} else {

				$qryfor_billto = "Select employees.initials, shipCity, shipState, dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_contact_date, next_date from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID where ID in (" . $qryfor_billto_str . ") $chkparentonly GROUP BY companyInfo.id "; //" . $skey . $sord . " 
			}
			//echo "<br/>" . $qryfor_billto . "<br/><br/>";
			db_b2b();
			$data_res = db_query($qryfor_billto);
			$data_res_No_Limit = db_query($qryfor_billto);
			$show = "All";

			if (tep_db_num_rows($data_res_No_Limit) > 0) {
				?>
        <table width="1300" border="0" cellspacing="1" cellpadding="1">
            <tr align="center">
                <td colspan="14" bgcolor="#FFCCCC">
                    <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
                        <b><?php echo "Record found in Bill To section: " . isset($row["name"]) . " - Total Records: " . tep_db_num_rows($data_res_No_Limit); ?></b>
                    </font>
                </td>
            </tr>
            <?php if (1 == 1 or $limit == 100000) { ?>
            <tr>
                <td width="5%" bgcolor="#D9F2FF">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=dt&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">DATE</a>
                    </font>
                </td>
                <td width="5%" bgcolor="#D9F2FF">
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=age&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">AGE</a>
                    </font>
                </td>
                <td width="10%" bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=contact&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">CONTACT</a>
                    </font>
                </td>
                <td width="21%" bgcolor="#D9F2FF">
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=cname&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">COMPANY
                            NAME</a></font>
                </td>
                <!-- <td bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nname&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">NICKNAME</a></font></td> -->
                <td width="8%" bgcolor="#D9F2FF">
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font>
                </td>
                <td bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=city&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">CITY</a>
                    </font>
                </td>
                <td bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=state&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">STATE</a>
                    </font>
                </td>
                <td bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=zip&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">ZIP</a>
                    </font>
                </td>
                <td bgcolor="#D9F2FF" align="center">
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ns&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Next
                            Step</a></font>
                </td>
                <td bgcolor="#D9F2FF" align="center">
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=lc&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Last<br>Communication</a>
                    </font>
                </td>
                <td bgcolor="#D9F2FF" align="center">
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nd&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Next
                            Communication</font>
                </td>
                <td bgcolor="#D9F2FF" align="center">
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                            href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ei&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Assigned
                            To</font>
                </td>
            </tr>
            <?php
							while ($data = array_shift($data_res)) {
								if ($data["nickname"] != "") {
									$nickname = $data["nickname"];
								} else {
									$tmppos_1 = strpos($data["company"], "-");
									if ($tmppos_1 != false) {
										$nickname = $data["company"];
									} else {
										if ($data["shipCity"] <> "" || $data["shipState"] <> "") {
											$nickname = $data["company"] . " - " . $data["shipCity"] . ", " . $data["shipState"];
										} else {
											$nickname = $data["company"];
										}
									}
								}

							?>
            <tr valign="middle">
                <td width="5%" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  timestamp_to_datetime($data["dateCreated"]);
																											?></font>
                </td>
                <td width="5%" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo date_diff_new($data["dateCreated"], "NOW");
																											?> Days</font>
                </td>
                <td width="10%" bgcolor="#E4E4E4" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["contact"] ?>
                    </font>
                </td>
                <td width="21%" bgcolor="#E4E4E4"><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $data["ID"] ?>">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $nickname; ?>
                        </font>
                    </a></td>
                <td width="3%" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["phone"] ?>
                    </font>
                </td>
                <td width="10%" bgcolor="#E4E4E4" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["city"] ?>
                    </font>
                </td>
                <td width="5%" bgcolor="#E4E4E4" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["state"] ?>
                    </font>
                </td>
                <td width="5%" bgcolor="#E4E4E4" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["zip"] ?></font>
                </td>
                <td width="15%" bgcolor="#E4E4E4" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["next_step"] ?>
                    </font>
                </td>
                <td width="10%" bgcolor="#E4E4E4" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data["last_contact_date"] != "") echo date('m/d/Y', strtotime($data["last_contact_date"])); ?>
                <td width="10%" <?php if ($data["next_date"] == date('Y-m-d')) { ?> bgcolor="#00FF00"
                    <?php } elseif ($data["next_date"] < date('Y-m-d') && $data["next_date"] != "") { ?>
                    bgcolor="#FF0000" <?php } else { ?> bgcolor="#E4E4E4" <?php } ?> align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data["next_date"] != "") echo date('m/d/Y', strtotime($data["next_date"])); ?>
                    </font>
                </td>

                <td bgcolor="#E4E4E4" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["initials"] ?>
                    </font>
                </td>

            </tr>

            <?php
							} // of the inactive or reactive if
							ob_flush();
						}
						echo "</table><p></p>";
					}
					//Search the details in the Bill to 
					//echo "sdfsdfsdfsdf---".$qryfor_sellto_str;
					//Search the details in the Sell to 
					if ($forbillto_sellto != "") {

						$qryfor_sellto = "Select employees.initials, shipCity, shipState, dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_contact_date, next_date from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID where ID in (" . $qryfor_sellto_str . ") and ID not in ( $forbillto_sellto ) $chkparentonly GROUP BY companyInfo.id "; //$skey . $sord
					} else {

						$qryfor_sellto = "Select employees.initials, shipCity, shipState, dateCreated, contact, ID, phone, company, nickname, city, state, zip, next_step, last_contact_date, next_date from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID where ID in (" . $qryfor_sellto_str . ") $chkparentonly GROUP BY companyInfo.id "; //$skey . $sord 
					}
					//echo "<br/>" . $qryfor_sellto . "<br/><br/>";
					db_b2b();
					$data_res = db_query($qryfor_sellto);
					$data_res_No_Limit = db_query($qryfor_sellto);
					$show = "All";
					if (tep_db_num_rows($data_res_No_Limit) > 0) {
						?>
            <table width="1300" border="0" cellspacing="1" cellpadding="1">
                <tr align="center">
                    <td colspan="14" bgcolor="#FFCCCC">
                        <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
                            <b><?php echo "Record found in Sell To section: " . isset($row["name"]) . " - Total Records: " . tep_db_num_rows($data_res_No_Limit); ?></b>
                        </font>
                    </td>
                </tr>
                <?php //if (($row["id"] != 24 AND $row["id"] != 46 AND $row["id"] != 50 AND $row["id"] != 49 AND $row["id"] != 43 AND $row["id"] != 44) OR $limit == 100000 ) { 
							?>
                <?php if (1 == 1 or $limit == 100000) { ?>
                <tr>
                    <td width="5%" bgcolor="#D9F2FF">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=dt&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">DATE</a>
                        </font>
                    </td>
                    <td width="5%" bgcolor="#D9F2FF">
                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=age&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">AGE</a>
                        </font>
                    </td>
                    <td width="10%" bgcolor="#D9F2FF" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=contact&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">CONTACT</a>
                        </font>
                    </td>
                    <td width="21%" bgcolor="#D9F2FF">
                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=cname&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">COMPANY
                                NAME</a></font>
                    </td>
                    <!-- <td bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nname&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">NICKNAME</a></font></td> -->
                    <td width="8%" bgcolor="#D9F2FF">
                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font>
                    </td>
                    <td bgcolor="#D9F2FF" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=city&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">CITY</a>
                        </font>
                    </td>
                    <td bgcolor="#D9F2FF" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=state&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">STATE</a>
                        </font>
                    </td>
                    <td bgcolor="#D9F2FF" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=zip&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">ZIP</a>
                        </font>
                    </td>
                    <td bgcolor="#D9F2FF" align="center">
                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ns&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Next
                                Step</a></font>
                    </td>
                    <td bgcolor="#D9F2FF" align="center">
                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=lc&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Last<br>Communication</a>
                        </font>
                    </td>
                    <td bgcolor="#D9F2FF" align="center">
                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nd&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Next
                                Communication</font>
                    </td>
                    <td bgcolor="#D9F2FF" align="center">
                        <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                                href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ei&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Assigned
                                To</font>
                    </td>
                </tr>
                <?php
								while ($data = array_shift($data_res)) {

									if ($data["nickname"] != "") {
										$nickname = $data["nickname"];
									} else {
										$tmppos_1 = strpos($data["company"], "-");
										if ($tmppos_1 != false) {
											$nickname = $data["company"];
										} else {
											if ($data["shipCity"] <> "" || $data["shipState"] <> "") {
												$nickname = $data["company"] . " - " . $data["shipCity"] . ", " . $data["shipState"];
											} else {
												$nickname = $data["company"];
											}
										}
									}

								?>
                <tr valign="middle">
                    <td width="5%" bgcolor="#E4E4E4">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  timestamp_to_datetime($data["dateCreated"]);
																												?></font>
                    </td>
                    <td width="5%" bgcolor="#E4E4E4">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo date_diff_new($data["dateCreated"], "NOW");
																												?> Days</font>
                    </td>
                    <td width="10%" bgcolor="#E4E4E4" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                            <?php echo $data["contact"] ?></font>
                    </td>
                    <td width="21%" bgcolor="#E4E4E4"><a target="_blank"
                            href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $data["ID"] ?>">
                            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $nickname; ?>
                            </font>
                        </a></td>
                    <td width="3%" bgcolor="#E4E4E4">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["phone"] ?>
                        </font>
                    </td>
                    <td width="10%" bgcolor="#E4E4E4" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["city"] ?>
                        </font>
                    </td>
                    <td width="5%" bgcolor="#E4E4E4" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["state"] ?>
                        </font>
                    </td>
                    <td width="5%" bgcolor="#E4E4E4" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["zip"] ?>
                        </font>
                    </td>
                    <td width="15%" bgcolor="#E4E4E4" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                            <?php echo $data["next_step"] ?></font>
                    </td>
                    <td width="10%" bgcolor="#E4E4E4" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                            <?php if ($data["last_contact_date"] != "") echo date('m/d/Y', strtotime($data["last_contact_date"])); ?>
                    <td width="10%" <?php if ($data["next_date"] == date('Y-m-d')) { ?> bgcolor="#00FF00"
                        <?php } elseif ($data["next_date"] < date('Y-m-d') && $data["next_date"] != "") { ?>
                        bgcolor="#FF0000" <?php } else { ?> bgcolor="#E4E4E4" <?php } ?> align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                            <?php if ($data["next_date"] != "") echo date('m/d/Y', strtotime($data["next_date"])); ?>
                        </font>
                    </td>

                    <td bgcolor="#E4E4E4" align="center">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                            <?php echo $data["initials"] ?></font>
                    </td>

                </tr>

                <?php
								} // of the inactive or reactive if
								ob_flush();
							}
							echo "</table><p></p>";
						}
					}
					//Search the details in the Sell to 
					?>
</form>
<?php

}


//
if ($_REQUEST["show"] == "search") {
	$searchcrit = $_REQUEST["searchterm"];
	//
	$search_res_sales_str = " ";
	$search_res_sales_loop_str = " ";
	if ($_REQUEST["search_res_sales"] == "Sales") {
		$search_res_sales_str = " and haveNeed = 'Need Boxes' ";
		$search_res_sales_loop_str = " and bs_status = 'Buyer' ";
	}
	if ($_REQUEST["search_res_sales"] == "Rescue") {
		$search_res_sales_str = " and haveNeed = 'Have Boxes' ";
		$search_res_sales_loop_str = " and bs_status = 'Seller' ";
	}

	$chkparentonly = " ";
	if (isset($_REQUEST["chkparentonly"])) {
		if ($_REQUEST["chkparentonly"] == "on") {
			$chkparentonly = " and parent_child = 'Parent' ";
		}
	}
	//
	if ($searchcrit != "" && $_REQUEST["fastsrch"] == true) {
		//
		//
		$x = "Select  companyInfo.id AS I from companyInfo where companyInfo.status IN ( " . showarrays($viewin) .  " ) ";
		//
		if ($_REQUEST["search_by"] != "any") {
			//
			if ($_REQUEST["search_by"] == "companyname") {
				$arrFields = array("nickname", "company", "comp_abbrv");
			}
			if ($_REQUEST["search_by"] == "compcityzip") {
				//echo "IN city zip";
				$arrFields = array("shipCity", "shipZip");
				//$x = $x . " AND companyInfo.name LIKE '" . str_replace("'", "\'" , $_REQUEST["searchterm"]) . "'";
			}
			if ($_REQUEST["search_by"] == "companid") {
				$arrFields = array("ID");
				//$x = $x . " AND companyInfo.ID LIKE '" . str_replace("'", "\'" , $_REQUEST["searchterm"]) . "'";
			}
			if (!empty($arrFields)) {
				if ($_REQUEST["andor"] == "exactmatch") {
					$i = 1;
					$x = $x . " AND ( ";
					foreach ($arrFields as $nm) {
						if ($i == 1) {;
						} else {
							$x = $x . " OR ";
						}
						$x = $x . " companyInfo." . $nm . " = '" . str_replace("'", "\'", $_REQUEST["searchterm"]) . "'";
						$i++;
					}
					$x = $x . " ) ";
				} else {
					$st = explode(' ', $_REQUEST["searchterm"]);

					$x = $x . " AND ( ";
					foreach ($st as $sti) {
						$i = 1;
						$x = $x . " ( ";
						foreach ($arrFields as $nm) {

							if ($i == 1) {;
							} else {
								$x = $x . " OR ";
							}
							$x = $x . " companyInfo." . $nm . " LIKE '%" . str_replace("'", "\'", $sti) . "%'";
							$i++;
						}
						$x = $x . " ) " . $_REQUEST["andor"] . " ";
					}

					if ($_REQUEST["andor"] == "AND") {
						$x = $x . " TRUE ) ";
					} else {
						$x = $x . " FALSE ) ";
					}

					if ($_REQUEST["state"] != "ALL")
						$x = $x . " AND companyInfo.state LIKE '" . $_REQUEST["state"] . "' ";
				}
				$x = $x . $search_res_sales_str . $chkparentonly;
				//
				$x = $x . " GROUP BY companyInfo.id ";
				//echo "<br>".$x."<br>";
				db_b2b();
				$data_res = db_query($x);
				//
				$comp_rows_num = tep_db_num_rows($data_res);
				//echo $comp_rows_num;
				if ($comp_data = array_shift($data_res)) {
					$b2b_comp_id = $comp_data["I"];
				} else {
					$b2b_comp_id = 0;
				}
				//
				if ($comp_rows_num == 1) {
					$redirect_url = "viewCompany.php?ID=" . $b2b_comp_id;
					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					//echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=\"javascript:window.open('" . $redirect_url . "','_blank');\">";
					echo "</noscript>";
					exit;
				} else {
					$redirect_url = "viewCompany.php?ID=" . $b2b_comp_id;
				}
			} //End arrayfield
			//
			if ($_REQUEST["search_by"] == "transpoquoteinvid") {
				if ($_REQUEST["search_res_sales"] == "" || $_REQUEST["search_res_sales"] == "Sales") {
					$sql = "SELECT id, inv_number, po_ponumber, warehouse_id FROM loop_transaction_buyer WHERE id = '" . str_replace("'", "\'", $searchcrit) . "' OR inv_number = '" . str_replace("'", "\'", $searchcrit) . "' OR po_ponumber = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					$loop_id_flg = "no";
					$inv_no_flg = "no";
					$po_no_flg = "no";
					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
						if ($searchcrit == $transid) {
							$loop_id_flg = "yes";
						}
						if ($searchcrit == $myrowsel["inv_number"]) {
							$inv_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["po_ponumber"]) {
							$po_no_flg = "yes";
						}
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . $wid . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && $transid > 0) {
							if ($myrowsel["bs_status"] == "Buyer") {
								if ($loop_id_flg == "yes" || $po_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_view";
								}
								if ($inv_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_payment";
								}
							}

							if ($myrowsel["bs_status"] == "Seller") {
								$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_ship";
							}
						}
						//
						if ($redirect_url != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
							echo "</noscript>";
							exit;
						}
					}

					$sql = "SELECT * FROM loop_transaction WHERE id = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . $wid . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && $transid > 0) {
							$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=seller_view";
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if ($redirect_url != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
							echo "</noscript>";
							exit;
						}
					}
				}
				//
				//For search based on Quote
				$compid = 0;
				$quote_id = "";
				$sql = "SELECT companyID, ID FROM quote WHERE (ID+3770) = '" . str_replace("'", "\'", $searchcrit) . "'  or poNumber='" . str_replace("'", "\'", $searchcrit) . "' " . $search_res_sales_str . $chkparentonly;
				db_b2b();
				$result = db_query($sql);
				$loop_reccnt = tep_db_num_rows($result);
				if ($myrowsel = array_shift($result)) {
					$compid = $myrowsel["companyID"];
					$quote_id = $myrowsel["ID"];
				}

				if ($loop_reccnt == 1) {
					$redirect_url = "viewCompany.php?ID=" . $compid . "&quoteid=" . $quote_id;

					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "</noscript>";
					exit;
				}
			} //End transaction ID, PO ID, INV ID
			//
			if ($_REQUEST["search_by"] == "transpoquoteinvid") {
				if ($_REQUEST["andor"] == "exactmatch") {
					$ship_to_str = "companyInfo.shipContact= '" . $_REQUEST["searchterm"] . "' OR companyInfo.shipemail = '" . $_REQUEST["searchterm"] . "' OR companyInfo.shipPhone ='" . $_REQUEST["searchterm"] . "' OR companyInfo.shipMobileno ='" . $_REQUEST["searchterm"] . "' OR companyInfo.shipto_main_line_ph = '" . $_REQUEST["searchterm"] . "'";
				} else {
					$ship_to_str = "companyInfo.shipContact LIKE '%" . $_REQUEST["searchterm"] . "%' OR companyInfo.shipemail LIKE '%" . $_REQUEST["searchterm"] . "%' OR companyInfo.shipPhone LIKE '%" . $_REQUEST["searchterm"] . "%' OR companyInfo.shipMobileno LIKE '%" . $_REQUEST["searchterm"] . "%' OR companyInfo.shipto_main_line_ph LIKE '%" . $_REQUEST["searchterm"] . "%'";
				}
				$shipqry = "select ID from companyInfo where " . $ship_to_str . $search_res_sales_str . $chkparentonly . " Group by companyInfo.id ";
				db_b2b();
				$ship_data_res = db_query($shipqry);
				//
				$contact_reccnt = tep_db_num_rows($ship_data_res);
				while ($shipdata = array_shift($ship_data_res)) {
					$b2b_contact_comp_id .= $shipdata["ID"];
				}
				if ($contact_reccnt == 1) {
					$redirect_url = "viewCompany.php?ID=" . $b2b_contact_comp_id;

					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "</noscript>";
					exit;
				}
				//
			}
			//-----------------------------------
			//
			if ($searchcrit == trim($searchcrit) && strpos($searchcrit, ' ') !== false) {
			} else {
				if ($_REQUEST["chkwater_inv"] == true) {
					//For search based on Quote
					$compid = 0;
					$warehouse_id = 0;
					$sql = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, water_transaction.id as rec_id  from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id WHERE invoice_number = '" . $searchcrit . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);
					if ($myrowsel = array_shift($result)) {
						$compid = $myrowsel["b2bid"];
						$warehouse_id = $myrowsel["wid"];
						$rec_id = $myrowsel["rec_id"];
					} else {
						$rec_id = 0;
					}

					if ($loop_reccnt == 1) {
						$redirect_url = "viewCompany-purchasing.php?ID=" . $compid . "&show=watertransactions&company_id=" . $warehouse_id . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . $rec_id . "&display=water_sort";

						echo "<script type=\"text/javascript\">";
						echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
						echo "</script>";
						echo "<noscript>";
						echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
						echo "</noscript>";
						exit;
					}
				} //end water is true
				//
				//sales / purchasing dd
				if ($_REQUEST["search_res_sales"] == "" || $_REQUEST["search_res_sales"] == "Sales") {
					//$sql = "SELECT * FROM loop_transaction_buyer WHERE id = '" .str_replace("'", "\'" , $searchcrit) . "' OR inv_number = '" .str_replace("'", "\'" , $searchcrit) . "' OR po_ponumber = '" .str_replace("'", "\'" , $searchcrit) . "'";
					$sql = "SELECT id, inv_number, po_ponumber, warehouse_id FROM loop_transaction_buyer WHERE id = '" . str_replace("'", "\'", $searchcrit) . "' OR inv_number = '" . str_replace("'", "\'", $searchcrit) . "' OR po_ponumber = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					$loop_id_flg = "no";
					$inv_no_flg = "no";
					$po_no_flg = "no";
					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
						if ($searchcrit == $transid) {
							$loop_id_flg = "yes";
						}
						if ($searchcrit == $myrowsel["inv_number"]) {
							$inv_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["po_ponumber"]) {
							$po_no_flg = "yes";
						}
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . $wid . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && $transid > 0) {
							if ($myrowsel["bs_status"] == "Buyer") {
								if ($loop_id_flg == "yes" || $po_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_view";
								}
								if ($inv_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_payment";
								}
							}

							if ($myrowsel["bs_status"] == "Seller") {
								$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_ship";
							}
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if ($redirect_url != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
							echo "</noscript>";
							exit;
						}
					}

					$sql = "SELECT * FROM loop_transaction WHERE id = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . $wid . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && $transid > 0) {
							$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=seller_view";
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if ($redirect_url != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
							echo "</noscript>";
							exit;
						}
					}
				} //End sales / purchasing dd
				//
				//For search based on Quote
				$compid = 0;
				$quote_id = "";
				$sql = "SELECT companyID, ID FROM quote WHERE (ID+3770) = '" . str_replace("'", "\'", $searchcrit) . "'";
				db_b2b();
				$result = db_query($sql);
				$loop_reccnt = tep_db_num_rows($result);
				if ($myrowsel = array_shift($result)) {
					$compid = $myrowsel["companyID"];
					$quote_id = $myrowsel["ID"];
				}

				if ($loop_reccnt == 1) {
					$redirect_url = "viewCompany.php?ID=" . $compid . "&quoteid=" . $quote_id;

					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "</noscript>";
					exit;
				}
				//
			} //end else searchit
			//
			if ($_REQUEST["andor"] == "exactmatch") {
				$sql = "SELECT * FROM loop_warehouse WHERE (
				 company_name = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_name = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_city = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_state = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_contact = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 company_email = '" . str_replace("'", "\'", $searchcrit) . "' ) $search_res_sales_loop_str";
			} else {
				$sql = "SELECT * FROM loop_warehouse WHERE (
				 company_name like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_name like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_city like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_state like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_contact like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 company_email like '%" . str_replace("'", "\'", $searchcrit) . "%' ) $search_res_sales_loop_str";
			}
			db();
			$result = db_query($sql);
			$loop_reccnt = tep_db_num_rows($result);
			if ($myrowsel = array_shift($result)) {
				if ($loop_reccnt == 1) {
					if ($transid > 0) {
						if ($myrowsel["bs_status"] == "Buyer") {
							$redirect_url = "search_results.php?id=" . $myrowsel["id"] . "&proc=View&searchcrit=&rec_type=&rec_id=$transid&display=buyer_view";
						}

						if ($myrowsel["bs_status"] == "Seller") {
							$redirect_url = "search_results.php?id=" . $myrowsel["id"] . "&proc=View&searchcrit=&rec_type=&rec_id=$transid&display=buyer_ship";
						}
					} else {
						if ($myrowsel["b2bid"] > 0) {
							$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
						}
					}
				}
			}

			if ($redirect_url != "") {
				echo "<script type=\"text/javascript\">";
				echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
				echo "</script>";
				echo "<noscript>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
				echo "</noscript>";
				exit;
			}
			//

		} else {
			//
			if ($searchcrit == trim($searchcrit) && strpos($searchcrit, ' ') !== false) {
			} else {
				if ($_REQUEST["chkwater_inv"] == true) {
					//For search based on Quote
					$compid = 0;
					$warehouse_id = 0;
					$sql = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, water_transaction.id as rec_id  from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id WHERE invoice_number = '" . $searchcrit . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);
					if ($myrowsel = array_shift($result)) {
						$compid = $myrowsel["b2bid"];
						$warehouse_id = $myrowsel["wid"];
						$rec_id = $myrowsel["rec_id"];
					}
					//
					if ($loop_reccnt == 1) {
						$redirect_url = "viewCompany-purchasing.php?ID=" . $compid . "&show=watertransactions&company_id=" . $warehouse_id . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . isset($rec_id) . "&display=water_sort";

						echo "<script type=\"text/javascript\">";
						echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
						echo "</script>";
						echo "<noscript>";
						echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
						echo "</noscript>";
						exit;
					}
				} //end water is true
				//
				//sales / purchasing dd
				if ($_REQUEST["search_res_sales"] == "" || $_REQUEST["search_res_sales"] == "Sales") {
					$sql = "SELECT id, inv_number, po_ponumber, warehouse_id FROM loop_transaction_buyer WHERE id = '" . str_replace("'", "\'", $searchcrit) . "' OR inv_number = '" . str_replace("'", "\'", $searchcrit) . "' OR po_ponumber = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					$loop_id_flg = "no";
					$inv_no_flg = "no";
					$po_no_flg = "no";
					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
						if ($searchcrit == $transid) {
							$loop_id_flg = "yes";
						}
						if ($searchcrit == $myrowsel["inv_number"]) {
							$inv_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["po_ponumber"]) {
							$po_no_flg = "yes";
						}
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . $wid . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && $transid > 0) {
							if ($myrowsel["bs_status"] == "Buyer") {
								if ($loop_id_flg == "yes" || $po_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_view";
								}
								if ($inv_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_payment";
								}
							}

							if ($myrowsel["bs_status"] == "Seller") {
								$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_ship";
							}
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if ($redirect_url != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
							echo "</noscript>";
							exit;
						}
					}

					$sql = "SELECT * FROM loop_transaction WHERE id = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . $wid . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && $transid > 0) {
							$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=seller_view";
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if ($redirect_url != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
							echo "</noscript>";
							exit;
						}
					}
				} //End sales / purchasing dd
				//
				//For search based on Quote
				$compid = 0;
				$quote_id = "";
				$sql = "SELECT companyID, ID FROM quote WHERE (ID+3770) = '" . str_replace("'", "\'", $searchcrit) . "'";
				db_b2b();
				$result = db_query($sql);
				$loop_reccnt = tep_db_num_rows($result);
				if ($myrowsel = array_shift($result)) {
					$compid = $myrowsel["companyID"];
					$quote_id = $myrowsel["ID"];
				}

				if ($loop_reccnt == 1) {
					$redirect_url = "viewCompany.php?ID=" . $compid . "&quoteid=" . $quote_id;

					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "</noscript>";
					exit;
				}
				//
			} //end else

			///-----------------------------------------------------

			//For search based company name
			$compid = 0;
			$quote_id = "";
			if ($_REQUEST["andor"] == "exactmatch") {
				$sql = "SELECT ID FROM companyInfo WHERE nickname = '" . str_replace("'", "\'", $searchcrit) . "' $search_res_sales_str $chkparentonly or contact = '" . str_replace("'", "\'", $searchcrit) . "' or email = '" . str_replace("'", "\'", $searchcrit) . "' or phone = '" . str_replace("'", "\'", $searchcrit) . "' or company = '" . str_replace("'", "\'", $searchcrit) . "'  or comp_abbrv = '" . str_replace("'", "\'", $searchcrit) . "'";
			} else {
				$sql = "SELECT ID FROM companyInfo WHERE nickname like '%" . str_replace("'", "\'", $searchcrit) . "%' $search_res_sales_str $chkparentonly or contact like '%" . str_replace("'", "\'", $searchcrit) . "%' or company like '%" . str_replace("'", "\'", $searchcrit) . "%' or comp_abbrv like '%" . str_replace("'", "\'", $searchcrit) . "%'";
			}
			db_b2b();
			$result = db_query($sql);
			$loop_reccnt = tep_db_num_rows($result);
			if ($myrowsel = array_shift($result)) {
				$compid = $myrowsel["ID"];
			}

			if ($loop_reccnt == 1) {
				$redirect_url = "viewCompany.php?ID=" . $compid;
				echo "<script type=\"text/javascript\">";
				echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
				echo "</script>";
				echo "<noscript>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
				echo "</noscript>";
				exit;
			}

			if ($_REQUEST["andor"] == "exactmatch") {
				$sql = "SELECT * FROM loop_warehouse WHERE (
				 company_name = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_name = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_city = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_state = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_contact = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 company_email = '" . str_replace("'", "\'", $searchcrit) . "' ) $search_res_sales_loop_str";
			} else {
				$sql = "SELECT * FROM loop_warehouse WHERE (
				 company_name like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_name like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_city like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_state like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_contact like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 company_email like '%" . str_replace("'", "\'", $searchcrit) . "%' ) $search_res_sales_loop_str";
			}
			db();
			$result = db_query($sql);
			$loop_reccnt = tep_db_num_rows($result);
			if ($myrowsel = array_shift($result)) {
				if ($loop_reccnt == 1) {
					if ($transid > 0) {
						if ($myrowsel["bs_status"] == "Buyer") {
							$redirect_url = "search_results.php?id=" . $myrowsel["id"] . "&proc=View&searchcrit=&rec_type=&rec_id=$transid&display=buyer_view";
						}

						if ($myrowsel["bs_status"] == "Seller") {
							$redirect_url = "search_results.php?id=" . $myrowsel["id"] . "&proc=View&searchcrit=&rec_type=&rec_id=$transid&display=buyer_ship";
						}
					} else {
						if ($myrowsel["b2bid"] > 0) {
							$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
						}
					}
				}
			}

			if ($redirect_url != "") {
				echo "<script type=\"text/javascript\">";
				echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
				echo "</script>";
				echo "<noscript>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
				echo "</noscript>";
				exit;
			}
		}
	}
} //End search



function showStatusesDashboard_all_records(array $arrVal, int $eid, int $limit, string $period): void
{

?>
<form name="frmstatusdashboard" id="frmstatusdashboard" method="post" action="">
    <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
    </div>
    <?php

		if ($_REQUEST["so"] == "A") {
			$so = "D";
		} else {
			$so = "A";
		}
		$skey = "";
		$sord = "";
		if ($_REQUEST["sk"] != "") {
			if ($eid > 0) {
				$tmp_sortorder = "";
				if ($_REQUEST["sk"] == "dt") {
					$tmp_sortorder = "companyInfo.dateCreated";
				} elseif ($_REQUEST["sk"] == "age") {
					$tmp_sortorder = "companyInfo.dateCreated";
				} elseif ($_REQUEST["sk"] == "cname") {
					$tmp_sortorder = "companyInfo.company";
				} elseif ($_REQUEST["sk"] == "qty") {
					$tmp_sortorder = "companyInfo.company";
				} elseif ($_REQUEST["sk"] == "nname") {
					$tmp_sortorder = "companyInfo.nickname";
				} elseif ($_REQUEST["sk"] == "nd") {
					$tmp_sortorder = "companyInfo.next_date";
				} elseif ($_REQUEST["sk"] == "ns") {
					$tmp_sortorder = "companyInfo.next_step";
				} elseif ($_REQUEST["sk"] == "ei") {
					$tmp_sortorder = "employees.initials";
				} elseif ($_REQUEST["sk"] == "lc") {
					$tmp_sortorder = "companyInfo.company";
				} else {
					$tmp_sortorder = "companyInfo." . $_REQUEST["sk"];
				}

				if ($so == "A") {
					$tmp_sort = "D";
				} else {
					$tmp_sort = "A";
				}
				$sql_qry = "update employees set sort_fieldname = '" . $tmp_sortorder . "', sort_order='" . $tmp_sort . "' where employeeID = " . $eid;
				db_b2b();
				db_query($sql_qry);
			}

			if ($_REQUEST["sk"] == "dt") {
				$skey = " ORDER BY companyInfo.dateCreated";
			} elseif ($_REQUEST["sk"] == "age") {
				$skey = " ORDER BY companyInfo.dateCreated";
			} elseif ($_REQUEST["sk"] == "contact") {
				$skey = " ORDER BY companyInfo.contact";
			} elseif ($_REQUEST["sk"] == "cname") {
				$skey = " ORDER BY companyInfo.company";
			} elseif ($_REQUEST["sk"] == "nname") {
				$skey = " ORDER BY companyInfo.nickname";
			} elseif ($_REQUEST["sk"] == "city") {
				$skey = " ORDER BY companyInfo.city";
			} elseif ($_REQUEST["sk"] == "state") {
				$skey = " ORDER BY companyInfo.state";
			} elseif ($_REQUEST["sk"] == "zip") {
				$skey = " ORDER BY companyInfo.zip";
			} elseif ($_REQUEST["sk"] == "nd") {
				$skey = " ORDER BY companyInfo.next_date";
			} elseif ($_REQUEST["sk"] == "ns") {
				$skey = " ORDER BY companyInfo.next_step";
			} elseif ($_REQUEST["sk"] == "ei") {
				$skey = " ORDER BY employees.initials";
			} elseif ($_REQUEST["sk"] == "lc") {
				$skey = " ORDER BY companyInfo.last_contact_date";
			} else {
				$skey = "";
			}

			if ($_REQUEST["so"] != "") {
				if ($_REQUEST["so"] == "A") {
					$sord = " ASC";
				} else {
					$sord = " DESC";
				}
			} else {
				$sord = " DESC";
			}
		} else {
			if ($eid > 0) {
				$sql_qry = "Select sort_fieldname, sort_order from employees where employeeID = " . $eid .  "";
				db_b2b();
				$dt_view_res = db_query($sql_qry);
				while ($row = array_shift($dt_view_res)) {
					if ($row["sort_fieldname"] != "") {
						if ($row["sort_order"] == "A") {
							$sord = " ASC";
						} else {
							$sord = " DESC";
						}
						$skey = " ORDER BY " . $row["sort_fieldname"];
					} else {
						$skey = " ORDER BY companyInfo.dateCreated ";
						$sord = " DESC";
					}
				}
			} else {
				$skey = " ORDER BY companyInfo.dateCreated ";
				$sord = " DESC";
			}
		}
		$total_record = 0;
		$all_toal_record = 0;
		?>
    <table class="past_due tbl_data" width="1300" border="0" cellspacing="1" cellpadding="1">
        <tr align="center">
            <td colspan="15" bgcolor="#FFCCCC">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>Showing All Account Status From
                        Each First 20</b></font>
            </td>
        </tr>
        <?php
			$sorturl = htmlentities($_SERVER['PHP_SELF'] . "?show=" . $_REQUEST['show'] . "&period=" . $_REQUEST['period']);
			?>
        <?php if (1 == 1 or $limit == 100000) { ?>
        <tr>
            <td width="5%" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    DATE
                    <a href="<?php echo $sorturl; ?>&sk=dt&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;">
                    </a>
                    <a href="<?php echo $sorturl; ?>&sk=dt&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td width="5%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">AGE
                    <a href="<?php echo $sorturl; ?>&sk=age&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=age&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td width="10%" bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">CONTACT
                    <a href="<?php echo $sorturl; ?>&sk=contact&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=contact&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td width="21%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    COMPANY NAME
                    <a href="<?php echo $sorturl; ?>&sk=cname&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=cname&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <!-- <td bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nname&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">NICKNAME</a></font></td> -->
            <td width="8%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    CITY
                    <a href="<?php echo $sorturl; ?>&sk=city&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=city&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    STATE
                    <a href="<?php echo $sorturl; ?>&sk=state&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=state&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    ZIP
                    <a href="<?php echo $sorturl; ?>&sk=zip&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=zip&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    Next Step
                    <a href="<?php echo $sorturl; ?>&sk=ns&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=ns&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    Last<br>Communication
                    <a href="<?php echo $sorturl; ?>&sk=lc&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=lc&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    Next Communication
                    <a href="<?php echo $sorturl; ?>&sk=nd&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=nd&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    Assigned To
                    <a href="<?php echo $sorturl; ?>&sk=ei&so=A"><img src="images/sort_asc.png" width="6px;"
                            height="12px;"></a>
                    <a href="<?php echo $sorturl; ?>&sk=ei&so=D"><img src="images/sort_desc.png" width="6px;"
                            height="12px;"></a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
                    Account Status
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Update</font>
            </td>
        </tr>

        <?php

				$flag_assignto_viewby = 0; //= 1 means in assignto mode and = 0 means in assign to and viewable mode
				$flag_assignto_viewby_str = "";

				$tmpdisplay_flg = "n";
				$dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($arrVal) .  ") union SELECT 0, 'Unassign', 'Unassign', 27, 0,''  FROM status ORDER BY sort_order";




				//echo $dt_view_qry . "<br>";
				$ctrl_cnt = 0;
				db_b2b();
				$dt_view_res = db_query($dt_view_qry);
				while ($row = array_shift($dt_view_res)) {

					if ($row["id"] != 58) {
						$x = "Select companyInfo.shipCity , companyInfo.shipState, companyInfo.shipZip, companyInfo.id AS I,  companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.status =" . $row["id"];
					} else {
						$x = "Select companyInfo.shipCity , companyInfo.shipState, companyInfo.shipZip, companyInfo.id AS I,  companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.special_ops = 1 ";
					}


					$x = $x . " GROUP BY companyInfo.id " . $skey . $sord . " ";
					$xL = $x . " LIMIT 0, 20";
					db_b2b();
					$data_res = db_query($xL);
					$data_res_No_Limit = db_query($x);
					$show = "All";

					//echo "<br/>" . $xL . "$eid <br/><br/>";

					$total_record = tep_db_num_rows($data_res_No_Limit);

					if ($total_record > 0) {

						if ($tmpdisplay_flg == "n") {
							$tmpdisplay_flg = "y";
							if ($flag_assignto_viewby == 1) {
								echo "Note: Records displayed are 'Assign to you'.<br>";
							} else {
								echo "Note: Records displayed are 'Assign to you and Viewable by you'.<br>";
							}
						}

						$forbillto_sellto = "";
						while ($data = array_shift($data_res)) {
							$forbillto_sellto = $forbillto_sellto  . $data["I"] . ", ";

							if ($data["NN"] != "") {
								$nickname = $data["NN"];
							} else {
								$tmppos_1 = strpos($data["CO"], "-");
								if ($tmppos_1 != false) {
									$nickname = $data["CO"];
								} else {
									if ($data["shipCity"] <> "" || $data["shipState"] <> "") {
										$nickname = $data["CO"] . " - " . $data["shipCity"] . ", " . $data["shipState"];
									} else {
										$nickname = $data["CO"];
									}
								}
							}

				?>
        <tr valign="middle" id="tbl_div<?php echo $ctrl_cnt; ?>">
            <td width="5%" bgcolor="#E4E4E4">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo  timestamp_to_datetime($data["D"]);
																																					?></font>
            </td>
            <td width="5%" bgcolor="#E4E4E4">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo date_diff_new($data["D"], "NOW");
																																					?> Days</font>
            </td>
            <td width="10%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["C"] ?></font>
            </td>
            <td width="21%" bgcolor="#E4E4E4"><a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $data["I"] ?>">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $nickname; ?><?php if ($data["LID"] > 0) echo "</b>"; ?>
                    </font>
                </a></td>
            <!-- <td bgcolor="#E4E4E4"><a href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $data["I"] ?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["NN"] ?></font></a></td> -->
            <td width="3%" bgcolor="#E4E4E4">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["PH"] ?></font>
            </td>
            <td width="10%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["shipCity"] ?></font>
            </td>
            <td width="5%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["shipState"] ?></font>
            </td>
            <td width="5%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php echo $data["shipZip"] ?></font>
            </td>
            <td width="15%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><textarea
                        id="note<?php echo $ctrl_cnt; ?>"><?php echo $data["NS"] ?></textarea></font>
            </td>

            <td width="10%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($data["LID"] > 0) echo "<b>"; ?><?php if ($data["LD"] != "") echo date('m/d/Y', strtotime($data["LD"])); ?>
            <td width="10%" <?php if ($data["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00"
                <?php } elseif ($data["ND"] < date('Y-m-d') && $data["ND"] != "") { ?> bgcolor="#FF0000"
                <?php } else { ?> bgcolor="#E4E4E4" <?php } ?> align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <input type="text" size="8" name="txt_next_step_dt" id="txt_next_step_dt<?php echo $ctrl_cnt; ?>"
                        value="<?php if ($data["LID"] > 0) ?><?php if ($data["ND"] != "") echo date('m/d/Y', strtotime($data["ND"])); ?>">
                    <a href="#"
                        onclick="cal2xx.select(document.frmstatusdashboard.txt_next_step_dt<?php echo $ctrl_cnt; ?>,'dtanchor1xx<?php echo $ctrl_cnt; ?>','yyyy-MM-dd'); return false;"
                        name="dtanchor1xx" id="dtanchor1xx<?php echo $ctrl_cnt; ?>"><img border="0"
                            src="images/calendar.jpg"></a>
                </font>
            </td>

            <td bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["EI"] ?></font>
            </td>
            <td bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $row["name"]; ?></font>
            </td>

            <td bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <input type="button" name="btnupdate" id="btnupdate" value="Update"
                        onclick="update_details(<?php echo $ctrl_cnt; ?>, <?php echo $data["I"] ?>)">
                </font>
            </td>

        </tr>

        <?php
							$ctrl_cnt = $ctrl_cnt + 1;
						} //  While Loop end here 
						ob_flush();
					}	// if record found

				}
				$all_toal_record = $all_toal_record + $total_record;
			}	// while row end here.

			echo "</table><p>";
			echo "<input type='hidden' id='st_row' value='0'>";
			echo "<input type='hidden' id='tot_all' value='" . $all_toal_record . "'>";

			if ($all_toal_record > 50) {
				echo "<span class='load_more' id='load_more'>Load more</span></p></p>";
			}
			?>
        <script>
        $(document).ready(function() {

            $('#load_more').click(function() {
                var row = Number($('#st_row').val());
                var allcount = Number($('#tot_all').val());
                var tblarray = <?php echo json_encode($arrVal); ?>;
                var numofrows = $('.tbl_data tr').length;
                numofrows = numofrows - 2;

                row = row + 20;

                if (numofrows <= allcount) {
                    $("#st_row").val(row);

                    $.ajax({
                        url: 'pipeline_table_loadmore.php',
                        type: 'post',
                        data: {
                            row: row,
                            tblarray: tblarray,
                            numofrows: numofrows
                        },
                        beforeSend: function() {
                            $("#load_more").text("Loading...");
                        },
                        success: function(response) {

                            // Setting little delay while displaying new content
                            setTimeout(function() {
                                // appending posts after last post with class="post"
                                $(".tbl_data tr:last").after(response).show()
                                    .fadeIn("slow");

                                if (numofrows >= allcount) {

                                    $('#load_more<?php echo isset($row['id']); ?>')
                                        .hide();
                                } else {
                                    $("#load_more<?php echo isset($row['id']); ?>")
                                        .text(
                                            "Load more");
                                }
                            }, 2000);


                        }
                    });
                }

            });
        });
        </script>

</form>
<?php

}




?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title><?php echo $initials; ?> - Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();
    </script>

    <?php
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	?>

    <script type="text/javascript">
    function todoitem_markcomp_dash(unqid) {
        window.location.href = "todolist_update.php?fromdash=y&unqid=" + unqid + "&markcomp=1";
    }

    function todoitem_showall() {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("todoshowall");
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_todo").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light_todo').style.display = 'block';

                document.getElementById('light_todo').style.left = (n_left - 400) + 'px';
                document.getElementById('light_todo').style.top = n_top - 40 + 'px';
                document.getElementById('light_todo').style.width = 700 + 'px';
            }
        }

        xmlhttp.open("GET", "todolist_view.php?fromdash=y&compid=0", true);
        xmlhttp.send();
    }

    function showcontact_details(compid) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("com_contact" + compid);
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_todo").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light_todo').style.display = 'block';

                document.getElementById('light_todo').style.left = (n_left + 50) + 'px';
                document.getElementById('light_todo').style.top = n_top - 40 + 'px';
                document.getElementById('light_todo').style.width = 400 + 'px';
            }
        }

        xmlhttp.open("GET", "dashboard-search-contact.php?compid=" + compid, true);
        xmlhttp.send();
    }

    function inv_summary(inv_summ_text_id, top_header_flg) {
        selectobject = document.getElementById("inv_summ_div");

        n_left = f_getPosition(selectobject, 'Left');
        n_top = f_getPosition(selectobject, 'Top');

        if (top_header_flg == 1) {
            var tabl_head =
                "<tr vAlign='left'>	<td bgColor='#e4e4e4' class='style12'><b>Actual</b></td> <td bgColor='#e4e4e4' class='style12'><b>After PO</b></td>";
            tabl_head = tabl_head +
                "<td bgColor='#e4e4e4' class='style12'><b>Last Month Quantity</b></td> <td bgColor='#e4e4e4' class='style12'><b>Availability</b></td>";
            tabl_head = tabl_head +
                "<td bgColor='#e4e4e4' class='style12' ><font size=1><b>Account Owner</b></font></td><td bgColor='#e4e4e4' class='style12' ><font size=1><b>Supplier</b></font></td><td bgColor='#e4e4e4' class='style12' ><font size=1><b>Ship From</b></font></td>";
            tabl_head = tabl_head +
                "<td bgColor='#e4e4e4' class='style12' width='100px;'><b>LxWxH</b></font></td><td bgColor='#e4e4e4' class='style12left' ><b>Description</b></font></td>";
            tabl_head = tabl_head +
                "<td bgColor='#e4e4e4' class='style12' width='150px;'><b>SKU</b></font></td><td bgColor='#e4e4e4' class='style12' ><b>Per Pallet</b></td>";
            tabl_head = tabl_head +
                "<td bgColor='#e4e4e4' class='style12' ><b>Per Trailer&nbsp;</b></td><td bgColor='#e4e4e4' class='style12' width='70px;'><b>Min FOB&nbsp;</b></td>";
            tabl_head = tabl_head +
                "<td bgColor='#e4e4e4' class='style12' width='70px;'><b>Cost&nbsp;</b></td><td bgColor='#e4e4e4' class='style12' ><b>Update</b></td>";
            tabl_head = tabl_head + "<td bgColor='#e4e4e4'class='style12left' ><b>Notes</b></td></tr>";
        } else {
            var tabl_head = "<tr ><td bgColor='#e4e4e4' class='style12'>";
            tabl_head = tabl_head + "Transaction ID</td><td bgColor='#e4e4e4' class='style12'>";
            tabl_head = tabl_head + "Company Name</td></tr>";

        }

        document.getElementById("light_todo").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/><table cellspacing='1' cellpadding='1' border='0'>" +
            tabl_head + document.getElementById(inv_summ_text_id).value + "</table>";
        document.getElementById('light_todo').style.display = 'block';

        document.getElementById('light_todo').style.left = (n_left - 100) + 'px';
        document.getElementById('light_todo').style.top = n_top - 250 + 'px';
        document.getElementById('light_todo').style.width = 1200 + 'px';

        document.getElementById("inv_summ_div").focus();
    }

    function showdealinprocess(emp_list_selected, sort_order_pre, sort) {
        document.getElementById("divdealinprocess").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("divdealinprocess").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET",
            "loop_index_load_tables_dashboard.php?dashboardflg=yes&tablenm=all_inbound&sort_order_pre=" +
            sort_order_pre + "&sort=" + sort + "&emp_list_selected=all", true);
        xmlhttp.send();
    }

    function showfullcustomerlist(emp_selected, sort_order_pre, sort, inmgrview, show_number) {

        document.getElementById("divdealinprocess").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert('xmlhttp.responseText -> '+xmlhttp.responseText);
                document.getElementById("divdealinprocess").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_full_customer_list.php?mgrview=yes&dashboardflg=yes&sort_order_pre=" +
            sort_order_pre + "&show_number=" + show_number + "&sort=" + sort + "&emp_selected=" + emp_selected +
            "&inmgrview=" + inmgrview, true);
        xmlhttp.send();
    }

    function showfullcustomerlist_openquotereq(emp_selected) {

        document.getElementById("divdealinprocess").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("divdealinprocess").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "dashboardnew_account_pipeline_all.php?show=fullistcustomer&emp_selected=" + emp_selected +
            "&mgrview=yes", true);
        xmlhttp.send();
    }

    function update_checked(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var txtpo_delivery_dt = "";
        if (document.getElementById('txtpo_delivery_dt' + cnt)) {
            txtpo_delivery_dt = document.getElementById('txtpo_delivery_dt' + cnt).value;
        }
        var tablenm = document.getElementById('tablenm' + cnt).value;

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&payrollchk=1&transid=" + transid +
            "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" + note + "&txtpo_delivery_dt=" +
            txtpo_delivery_dt, true);
        xmlhttp.send();
    }

    function update_checked_undo(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var txtpo_delivery_dt = "";
        if (document.getElementById('txtpo_delivery_dt' + cnt)) {
            txtpo_delivery_dt = document.getElementById('txtpo_delivery_dt' + cnt).value;
        }
        var tablenm = document.getElementById('tablenm' + cnt).value;

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&payrollchkundo=1&transid=" + transid +
            "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" + note + "&txtpo_delivery_dt=" +
            txtpo_delivery_dt, true);
        xmlhttp.send();
    }

    function update_cancelbtn(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var txtpo_delivery_dt = "";
        if (document.getElementById('txtpo_delivery_dt' + cnt)) {
            txtpo_delivery_dt = document.getElementById('txtpo_delivery_dt' + cnt).value;
        }
        var tablenm = document.getElementById('tablenm' + cnt).value;

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&cancelflg=1&transid=" + transid +
            "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" + note + "&txtpo_delivery_dt=" +
            txtpo_delivery_dt, true);
        xmlhttp.send();
    }

    function update_movepending(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var txtpo_delivery_dt = "";
        if (document.getElementById('txtpo_delivery_dt' + cnt)) {
            txtpo_delivery_dt = document.getElementById('txtpo_delivery_dt' + cnt).value;
        }
        var tablenm = document.getElementById('tablenm' + cnt).value;

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&movepending=1&transid=" + transid +
            "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" + note + "&txtpo_delivery_dt=" +
            txtpo_delivery_dt, true);
        xmlhttp.send();
    }


    function update_movepending_preorder(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var txtpo_delivery_dt = "";
        if (document.getElementById('txtpo_delivery_dt' + cnt)) {
            txtpo_delivery_dt = document.getElementById('txtpo_delivery_dt' + cnt).value;
        }
        var tablenm = document.getElementById('tablenm' + cnt).value;

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&movependingpreorder=1&transid=" + transid +
            "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" + note + "&txtpo_delivery_dt=" +
            txtpo_delivery_dt, true);
        xmlhttp.send();
    }

    function update_goodtoship(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var txtpo_delivery_dt = "";
        if (document.getElementById('txtpo_delivery_dt' + cnt)) {
            txtpo_delivery_dt = document.getElementById('txtpo_delivery_dt' + cnt).value;
        }
        var tablenm = document.getElementById('tablenm' + cnt).value;

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&goodtoship=1&transid=" + transid +
            "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" + note + "&txtpo_delivery_dt=" +
            txtpo_delivery_dt, true);
        xmlhttp.send();
    }

    function update_details_6_7(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var tablenm = document.getElementById('tablenm' + cnt).value;

        var txtfr_pickup_date = document.getElementById('txtfr_pickup_date' + cnt).value;
        var txtfreight_booked_delivery_date = document.getElementById('txtfreight_booked_delivery_date' + cnt).value;
        var freight_booking_vendor = document.getElementById('freight_booking_vendor' + cnt).value;
        var txt_quotedamount = "";
        if (document.getElementById('txt_quotedamount' + cnt)) {
            txt_quotedamount = document.getElementById('txt_quotedamount' + cnt).value;
        }
        var txt_booked_delivery_cost = "";
        if (document.getElementById('txt_booked_delivery_cost' + cnt)) {
            txt_booked_delivery_cost = document.getElementById('txt_booked_delivery_cost' + cnt).value;
        }

        var entinfo_link = "";
        if (document.getElementById('txt_entinfo_link' + cnt)) {
            entinfo_link = document.getElementById('txt_entinfo_link' + cnt).value;
        }

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&updatedata=67&transid=" + transid +
            "&entinfo_link=" + entinfo_link + "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" +
            note + "&txtfr_pickup_date=" + txtfr_pickup_date + "&txtfreight_booked_delivery_date=" +
            txtfreight_booked_delivery_date + "&freight_booking_vendor=" + freight_booking_vendor +
            "&txt_quotedamount=" + txt_quotedamount + "&txt_booked_delivery_cost=" + txt_booked_delivery_cost, true);
        xmlhttp.send();
    }

    function update_details_6_7_b(cnt) {
        var transid = document.getElementById('transid_b' + cnt).value;
        var note = document.getElementById('note_b' + cnt).value;
        var warehouseid = document.getElementById('warehouseid_b' + cnt).value;
        var tablenm = document.getElementById('tablenm_b' + cnt).value;
        var txtfr_pickup_date_delivery = document.getElementById('txtfr_pickup_date_delivery' + cnt).value;
        var txtfr_dock_appointment_delivery = document.getElementById('txtfr_dock_appointment_delivery' + cnt).value;

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";
        //alert('cnt ->' +cnt+' / transid -> '+transid+' / warehouseid -> '+warehouseid+' / tablenm -> '+tablenm+' / txtfr_pickup_date_delivery -> '+txtfr_pickup_date_delivery+' / txtfr_dock_appointment_delivery ->'+txtfr_dock_appointment_delivery+' / note-> '+note);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&updatedata=67_b&transid=" + transid +
            "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" + note +
            "&txtfr_pickup_date_delivery=" + txtfr_pickup_date_delivery + "&txtfr_dock_appointment_delivery=" +
            txtfr_dock_appointment_delivery + "&updateRow=6b", true);
        xmlhttp.send();
    }

    function update_details_6(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var tablenm = document.getElementById('tablenm' + cnt).value;

        var txtfr_pickup_date = document.getElementById('txtfr_pickup_date' + cnt).value;
        var txtfreight_booked_delivery_date = '';
        if (document.getElementById('txtfreight_booked_delivery_date' + cnt)) {
            txtfreight_booked_delivery_date = document.getElementById('txtfreight_booked_delivery_date' + cnt).value;
        }
        var txtfr_dock_appointment = '';
        if (document.getElementById('txtfr_dock_appointment' + cnt)) {
            txtfr_dock_appointment = document.getElementById('txtfr_dock_appointment' + cnt).value;
        }

        var freight_booking_vendor = '';
        if (document.getElementById('freight_booking_vendor' + cnt)) {
            freight_booking_vendor = document.getElementById('freight_booking_vendor' + cnt).value;
        }
        var txt_quotedamount = "";
        if (document.getElementById('txt_quotedamount' + cnt)) {
            txt_quotedamount = document.getElementById('txt_quotedamount' + cnt).value;
        }
        var txt_booked_delivery_cost = "";
        if (document.getElementById('txt_booked_delivery_cost' + cnt)) {
            txt_booked_delivery_cost = document.getElementById('txt_booked_delivery_cost' + cnt).value;
        }

        var entinfo_link = "";
        if (document.getElementById('txt_entinfo_link' + cnt)) {
            entinfo_link = document.getElementById('txt_entinfo_link' + cnt).value;
        }

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&updatedata=6&transid=" + transid +
            "&entinfo_link=" + entinfo_link + "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" +
            note + "&txtfr_pickup_date=" + txtfr_pickup_date + "&txtfreight_booked_delivery_date=" +
            txtfreight_booked_delivery_date + "&freight_booking_vendor=" + freight_booking_vendor +
            "&txt_quotedamount=" + txt_quotedamount + "&txt_booked_delivery_cost=" + txt_booked_delivery_cost +
            "&txtfr_dock_appointment=" + txtfr_dock_appointment, true);
        xmlhttp.send();
    }

    function update_details_7(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var tablenm = document.getElementById('tablenm' + cnt).value;

        var txtfr_pickup_date = document.getElementById('txtfr_pickup_date' + cnt).value;
        var txtfreight_booked_delivery_date = '';
        if (document.getElementById('txtfreight_booked_delivery_date' + cnt)) {
            txtfreight_booked_delivery_date = document.getElementById('txtfreight_booked_delivery_date' + cnt).value;
        }
        var txtfr_dock_appointment = '';
        if (document.getElementById('txtfr_dock_appointment' + cnt)) {
            txtfr_dock_appointment = document.getElementById('txtfr_dock_appointment' + cnt).value;
        }

        var freight_booking_vendor = '';
        if (document.getElementById('freight_booking_vendor' + cnt)) {
            freight_booking_vendor = document.getElementById('freight_booking_vendor' + cnt).value;
        }

        var txt_quotedamount = "";
        if (document.getElementById('txt_quotedamount' + cnt)) {
            txt_quotedamount = document.getElementById('txt_quotedamount' + cnt).value;
        }
        var txt_booked_delivery_cost = "";
        if (document.getElementById('txt_booked_delivery_cost' + cnt)) {
            txt_booked_delivery_cost = document.getElementById('txt_booked_delivery_cost' + cnt).value;
        }

        var entinfo_link = "";
        if (document.getElementById('txt_entinfo_link' + cnt)) {
            entinfo_link = document.getElementById('txt_entinfo_link' + cnt).value;
        }

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&updatedata=7&transid=" + transid +
            "&entinfo_link=" + entinfo_link + "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" +
            note + "&txtfr_pickup_date=" + txtfr_pickup_date + "&txtfreight_booked_delivery_date=" +
            txtfreight_booked_delivery_date + "&freight_booking_vendor=" + freight_booking_vendor +
            "&txt_quotedamount=" + txt_quotedamount + "&txt_booked_delivery_cost=" + txt_booked_delivery_cost +
            "&txtfr_dock_appointment=" + txtfr_dock_appointment, true);
        xmlhttp.send();
    }

    function update_details_8_9(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var tablenm = document.getElementById('tablenm' + cnt).value;

        var txt_actual_pickup_date = "";
        var txtfreight_booked_delivery_date = document.getElementById('txtfreight_booked_delivery_date' + cnt).value;
        var freight_booking_vendor = 0;
        var txt_quotedamount = "";
        if (document.getElementById('txt_quotedamount' + cnt)) {
            txt_quotedamount = document.getElementById('txt_quotedamount' + cnt).value;
        }
        var txt_booked_delivery_cost = "";
        if (document.getElementById('txt_booked_delivery_cost' + cnt)) {
            txt_booked_delivery_cost = document.getElementById('txt_booked_delivery_cost' + cnt).value;
        }
        var txt_additional_freight_costs;
        if (document.getElementById('txt_additional_freight_costs' + cnt)) {
            txt_additional_freight_costs = document.getElementById('txt_additional_freight_costs' + cnt).value;
        }

        var entinfo_link = "";
        if (document.getElementById('txt_entinfo_link' + cnt)) {
            entinfo_link = document.getElementById('txt_entinfo_link' + cnt).value;
        }

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&updatedata=89&transid=" + transid +
            "&txt_additional_freight_costs=" + txt_additional_freight_costs + "&entinfo_link=" + entinfo_link +
            "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" + note + "&txt_actual_pickup_date=" +
            txt_actual_pickup_date + "&txtfreight_booked_delivery_date=" + txtfreight_booked_delivery_date +
            "&freight_booking_vendor=" + freight_booking_vendor + "&txt_quotedamount=" + txt_quotedamount +
            "&txt_booked_delivery_cost=" + txt_booked_delivery_cost, true);
        xmlhttp.send();
    }

    function update_details_b2bsurvey_ignore(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var tablenm = document.getElementById('tablenm' + cnt).value;

        document.getElementById("tbl_div" + cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&updatedata=896&transid=" + transid +
            "&warehouseid=" + warehouseid + "&tablenm=" + tablenm, true);
        xmlhttp.send();
    }

    function update_details(ctrl_cnt, comp_id) {
        var notes_data = encodeURIComponent(document.getElementById('note' + ctrl_cnt).value);
        var notes_date = encodeURIComponent(document.getElementById('txt_next_step_dt' + ctrl_cnt).value);
        document.getElementById("tbl_div" + ctrl_cnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_div" + ctrl_cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_note_updatedata.php?updatedata=1&comp_id=" + comp_id + "&ctrl_cnt=" + ctrl_cnt +
            "&notes_data=" + notes_data + "&notes_date=" + notes_date, true);
        xmlhttp.send();
    }



    function load_div(id) {
        var element = document.getElementById(id); //replace elementId with your element's Id.
        var rect = element.getBoundingClientRect();
        var elementLeft, elementTop; //x and y
        var scrollTop = document.documentElement.scrollTop ?
            document.documentElement.scrollTop : document.body.scrollTop;
        var scrollLeft = document.documentElement.scrollLeft ?
            document.documentElement.scrollLeft : document.body.scrollLeft;
        elementTop = rect.top + scrollTop;
        elementLeft = rect.left + scrollLeft;

        document.getElementById("light").innerHTML = document.getElementById(id).innerHTML;
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        document.getElementById('light').style.left = '100px';
        document.getElementById('light').style.top = elementTop + 100 + 'px';
    }

    function load_div_spin(id) {
        var element = document.getElementById(id); //replace elementId with your element's Id.
        var rect = element.getBoundingClientRect();
        var elementLeft, elementTop; //x and y
        var scrollTop = document.documentElement.scrollTop ?
            document.documentElement.scrollTop : document.body.scrollTop;
        var scrollLeft = document.documentElement.scrollLeft ?
            document.documentElement.scrollLeft : document.body.scrollLeft;
        elementTop = rect.top + scrollTop;
        elementLeft = rect.left + scrollLeft;

        document.getElementById("light").innerHTML = document.getElementById(id).innerHTML;
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        document.getElementById('light').style.left = '100px';
        document.getElementById('light').style.top = elementTop + 100 + 'px';
    }


    function close_div() {
        document.getElementById('light').style.display = 'none';
    }

    function display_preoder_sel(tmpcnt, reccnt, box_id, wid) {
        if (reccnt > 0) {

            if (document.getElementById('inventory_preord_org_top_' + tmpcnt).style.display == 'table-row') {
                document.getElementById('inventory_preord_org_top_' + tmpcnt).style.display = 'none';
            } else {
                document.getElementById('inventory_preord_org_top_' + tmpcnt).style.display = 'table-row';
            }

            document.getElementById("inventory_preord_org_middle_div_" + tmpcnt).innerHTML =
                "<br><br>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("inventory_preord_org_middle_div_" + tmpcnt).innerHTML = xmlhttp
                        .responseText;
                }
            }

            xmlhttp.open("GET", "inventory_preorder_childtable.php?box_id=" + box_id + "&wid=" + wid, true);
            xmlhttp.send();

        }
    }

    function show_val() {

        var skillsSelect = document.getElementById("unpaid_paid");
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        document.getElementById("paidunpaid_flg").value = selectedText;

        if (selectedText == 'Paid') {
            document.getElementById("showcal").style.display = "inline";
        }
        if (selectedText == 'Unpaid') {
            document.getElementById("showcal").style.display = "none";
        }
        if (selectedText == 'Invoice Not Paid') {
            document.getElementById("showcal").style.display = "none";
        }

    }

    /*function loadmainpg() 
    {
      if(document.getElementById ("paidunpaid_flg").value == "Paid")
      {
    	if(document.getElementById('date_from').value !="" && document.getElementById('date_to').value !="")
    	{
    		  //document.frmactive.action = "adminpg.php";
    	}
    	else
    	{
    		  alert("Please select Mark as paid date From/To.");
    		  return false;
    	}
      } 
    	else
    	{
    	  
    	}
    }*/
    function set_paidflg(data) {

        if (document.getElementById("match_confirmed").value == "commissions_paid") {
            document.getElementById("paidunpaid_flg").value = "Paid";
            document.getElementById("showcal").style.display = "inline";
        } else {
            document.getElementById("showcal").style.display = "none";
        }
    }

    function loadmainpg() {
        //document.getElementById("paidunpaid_flg").value = value;
        if (document.getElementById("match_confirmed").value == "commissions_paid") {
            document.getElementById("paidunpaid_flg").value = "Paid";
        } else {
            document.getElementById("paidunpaid_flg").value = "Unpaid";
        }

        //if(document.getElementById ("paidunpaid_flg").value == "Paid")
        if (document.getElementById("match_confirmed").value == "commissions_paid") {
            if (document.getElementById('date_from').value != "" && document.getElementById('date_to').value != "") {
                //document.frmactive.action = "adminpg.php";
                document.rptcommission.submit();
            } else {
                alert("Please select Mark as paid date From/To.");
                return false;
            }
        } else {
            document.rptcommission.submit();
        }
    }

    function showtable(tablenm, sort_order_pre, sort, empid) {
        var emp_list_selected = '';
        document.getElementById("divdealinprocess").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("divdealinprocess").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "loop_index_load_tables.php?tablenm=" + tablenm + "&sort_order_pre=" + sort_order_pre +
            "&empid=" + empid + "&sort=" + sort + "&emp_list_selected=" + emp_list_selected, true);
        xmlhttp.send();
    }

    function f_getPosition(e_elemRef, s_coord) {
        var n_pos = 0,
            n_offset,
            //e_elem = selectobject;
            e_elem = e_elemRef;
        while (e_elem) {
            n_offset = e_elem["offset" + s_coord];
            n_pos += n_offset;
            e_elem = e_elem.offsetParent;

        }
        e_elem = e_elemRef;
        //e_elem = selectobject;
        while (e_elem != document.body) {
            n_offset = e_elem["windows" + s_coord];
            if (n_offset && e_elem.style.overflow == 'windows')
                n_pos -= n_offset;
            e_elem = e_elem.parentNode;
        }

        return n_pos;

    }

    function show_sidebar() {
        var selectobject;
        selectobject = document.getElementById("searchterm");

        var n_left = f_getPosition(selectobject, 'Left');
        //alert(n_left);
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById("light").innerHTML = document.getElementById("sidebar").innerHTML;
        document.getElementById('light').style.display = 'block';

        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 60 + 'px';
        document.getElementById('light').style.width = 550 + 'px';
        document.getElementById('light').style.height = 200 + 'px';

    }

    function show_fast_search() {
        var selectobject;
        selectobject = document.getElementById("searchterm");

        var n_left = f_getPosition(selectobject, 'Left');
        //alert(n_left);
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById("light").innerHTML = document.getElementById("fast_search_helptext").innerHTML;
        document.getElementById('light').style.display = 'block';

        document.getElementById('light').style.left = n_left + 150 + 'px';
        document.getElementById('light').style.top = n_top + 30 + 'px';
        document.getElementById('light').style.width = 350 + 'px';
        document.getElementById('light').style.height = 100 + 'px';

    }

    function hide_sidebar() {
        document.getElementById('light').style.display = 'none';
    }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
    .load_more {
        width: 99%;
        background: #D9F2FF;
        text-align: center;
        padding: 10px 25px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        border-radius: 5px;
    }

    .load_more:hover {
        cursor: pointer;
    }

    .viewable_txt {
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
        font-size: 13px;
        color: #666666;
    }

    .viewable_frm {
        border: 1px solid #E0E0E0;
        padding: 0px 10px 4px 10px;
        border-radius: 7px;
    }

    .viewable_dd_style {
        border: 1px solid #ccc !important;
        font-size: 12px;
    }

    .viewable_button {
        background-color: #D4D4D4;
        border: none;
        color: #464646;
        padding: 2px 10px;
        text-decoration: none;
        margin: 4px 2px;
        cursor: pointer;
        border: 1px solid #4E4E4E;
        font-size: 12px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
    }

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

    .main_data_css {
        margin: 0 auto;
        width: 100%;
        height: auto;
        clear: both !important;
        padding-top: 35px;
        margin-left: 10px;
        margin-right: 10px;
    }

    .dashboard_heading {
        margin-top: 20px;
        width: 100%;
        font-size: 24px;
        /*font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif!important;*/
        font-family: 'Titillium Web', sans-serif;
        font-weight: 600;
    }

    .newtxttheam_withdot {
        font-family: Arial, Helvetica, sans-serif;
        font-size: xx-small;
        padding: 4px;
        background-color: #e4e4e4;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .newtxttheam_withdot_light {
        font-family: Arial, Helvetica, sans-serif;
        font-size: xx-small;
        padding: 4px;
        background-color: #f4f5ef;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .newtxttheam_withdot_red {
        font-family: Arial, Helvetica, sans-serif;
        font-size: xx-small;
        padding: 4px;
        background-color: red;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .highlight_row {
        background-color: #df2f2f;
    }

    .rec_row {
        background-color: #e4e4e4;
    }
    </style>
    <style type="text/css">
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
        left: 12%;
        background: #ffffff;
    }

    span.infotxt span {
        position: absolute;
        left: -9999px;
        margin: 1px 0 0 0px;
        padding: 0px 3px 3px 3px;
        border-style: solid;
        border-color: black;
        border-width: 1px;
    }

    span.infotxt:hover span {
        margin: 1px 0 0 170px;
        background: #ffffff;
        z-index: 6;
    }

    .style12_new1 {
        font-size: small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: left;
    }

    .style12_new_top {
        font-size: small;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #FF9900;
        text-align: center;
    }

    .style12_new_center {
        font-size: small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: center;
    }

    .style12_new2 {
        font-size: small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: right;
    }

    .txtstyle_color {
        font-family: arial;
        font-size: 12;
        height: 16px;
        background: #ABC5DF;
    }

    .header_td_style {
        font-family: arial;
        font-size: 12;
        height: 16px;
        background: #ABC5DF;
    }

    .white_content_search {
        display: none;
        position: absolute;
        padding: 5px;
        border: 1px solid black;
        background-color: #FFF8C6;
        z-index: 1002;
        overflow: auto;
        color: black;
        border-radius: 8px;
        padding: 5px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .black_overlay {
        display: none;
        position: absolute;
    }

    .white_content {
        display: none;
        position: absolute;
        border: 1px solid #909090;
        background-color: white;
        overflow: auto;
        height: 600px;
        width: 850px;
        z-index: 999999;
        margin: 0px 0 0 0px;
        padding: 10px 10px 10px 10px;
        border-color: black;
        /*border-width:2px;*/
        overflow: auto;
        border-radius: 8px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    </style>

    <script type="text/javascript">
    function ex_emp_status(viewin, eid, show_number, dtrange) {
        var display_div = "StatusesDashboard_div";

        if (document.getElementById(display_div).innerHTML == "") {
            document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

            if (document.getElementById(display_div).style.display == "none") {
                document.getElementById(display_div).style.display = "block";
            } else {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("span_emp_status").style.display = "none";
                        document.getElementById(display_div).innerHTML = xmlhttp.responseText;
                    }
                }
            }

            xmlhttp.open("POST", "dash_emp_status.php?viewin=" + viewin + "&eid=" + eid + "&show_number=" +
                show_number + "&dtrange=" + dtrange, true);
            xmlhttp.send();
        } else {
            document.getElementById("span_emp_status").style.display = "none";
            document.getElementById(display_div).style.display = "block";
        }
    }

    function colp_emp_status() {
        var display_div = "StatusesDashboard_div";
        document.getElementById(display_div).style.display = "none";
        document.getElementById("span_emp_status").style.display = "block";
    }

    function ex_close_deal_pipline(initials, dashboard_view) {
        var display_div = "close_deal_pipline_div";

        if (document.getElementById(display_div).innerHTML == "") {
            document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

            if (document.getElementById(display_div).style.display == "none") {
                document.getElementById(display_div).style.display = "block";
            } else {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("span_close_deal_pipline").style.display = "none";
                        document.getElementById(display_div).innerHTML = xmlhttp.responseText;
                    }
                }
            }

            xmlhttp.open("POST", "dash_closed_deal_pipeline.php?initials=" + initials + "&dashboard_view=" +
                dashboard_view, true);
            xmlhttp.send();
        } else {
            document.getElementById("span_close_deal_pipline").style.display = "none";
            document.getElementById(display_div).style.display = "block";
        }
    }

    function colp_close_deal_pipline() {
        var display_div = "close_deal_pipline_div";
        document.getElementById(display_div).style.display = "none";
        document.getElementById("span_close_deal_pipline").style.display = "block";
    }

    function ex_rev_tracker(initials, dashboard_view) {
        var display_div = "rev_tracker_div";

        if (document.getElementById(display_div).innerHTML == "") {
            document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

            if (document.getElementById(display_div).style.display == "none") {
                document.getElementById(display_div).style.display = "block";
            } else {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("span_rev_tracker").style.display = "none";
                        document.getElementById(display_div).innerHTML = xmlhttp.responseText;
                    }
                }
            }

            xmlhttp.open("POST", "dash_revenue_tracker.php?initials=" + initials + "&dashboard_view=" + dashboard_view,
                true);
            xmlhttp.send();
        } else {
            document.getElementById("span_rev_tracker").style.display = "none";
            document.getElementById(display_div).style.display = "block";
        }
    }

    function colp_rev_tracker() {
        var display_div = "rev_tracker_div";
        document.getElementById(display_div).style.display = "none";
        document.getElementById("span_rev_tracker").style.display = "block";
    }

    function ex_dash_po_enter(st_date, end_date, po_key, emp_initial, dashboardview) {
        var display_div = "po_entered_display_" + po_key;

        if (document.getElementById(display_div).innerHTML == "") {
            document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

            if (document.getElementById(display_div).style.display == "none") {
                document.getElementById(display_div).style.display = "block";
            } else {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("hide_tr").style.display = "none";
                        document.getElementById(display_div).innerHTML = xmlhttp.responseText;
                    }
                }
            }

            xmlhttp.open("POST", "dash_po_entered.php?st_date=" + st_date + "&end_date=" + end_date + "&po_key=" +
                po_key + "&emp_initial=" + emp_initial + "&dashboardview=" + dashboardview, true);
            xmlhttp.send();
        } else {
            document.getElementById("hide_tr").style.display = "none";
            document.getElementById(display_div).style.display = "block";
        }
    }

    function colp_dash_po_enter(po_key) {
        var display_div = "po_entered_display_" + po_key;
        document.getElementById(display_div).style.display = "none";
        document.getElementById("hide_tr").style.display = "block";
    }
    //New Deal Spin expand and collaps
    function ex_dash_deal_spin(emp_initial, dashboardview) {
        //alert(dashboardview);
        var display_div = "deal_spin_display";
        if (document.getElementById(display_div).innerHTML == "") {
            document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

            if (document.getElementById(display_div).style.display == "none") {
                document.getElementById(display_div).style.display = "block";
            } else {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("hide_tr_spin").style.display = "none";
                        document.getElementById(display_div).innerHTML = xmlhttp.responseText;
                    }
                }
            }

            xmlhttp.open("POST", "dash_new_deal_spin.php?initial=" + emp_initial + "&dashboardview=" + dashboardview,
                true);
            xmlhttp.send();
        } else {
            document.getElementById("hide_tr_spin").style.display = "none";
            document.getElementById(display_div).style.display = "block";
        }
    }

    function colp_dash_deal_spin() {
        var display_div = "deal_spin_display";
        document.getElementById(display_div).style.display = "none";
        document.getElementById("hide_tr_spin").style.display = "block";
    }
    </script>
</head>

<body>
    <script type="text/javascript" src="wz_tooltip.js"></script>
    <div id="light_todo" class="white_content"></div>
    <div id="fade_todo" class="black_overlay"></div>
    <?php
	//------------------------------------------------------------------------------
	//New match inventory

	function showinventory_fordashboard_invmatch_new(string $g_timing, string $sort_g_tool, string $sort_g_view): void
	{
	?>
    <script>
    function displayboxdata_invnew(colid, sortflg, box_type_cnt) {
        document.getElementById("btype" + box_type_cnt).innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        //
        var sort_g_view = document.getElementById("sort_g_view").value;
        var sort_g_tool = document.getElementById("sort_g_tool").value;
        var g_timing = document.getElementById("g_timing").value;
        //

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert(xmlhttp.responseText);
                document.getElementById("btype" + box_type_cnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "dashboard_inv_sort.php?colid=" + colid + "&sortflg=" + sortflg + "&sort_g_view=" +
            sort_g_view + "&sort_g_tool=" + sort_g_tool + "&g_timing=" + g_timing + "&box_type_cnt=" + box_type_cnt,
            true);
        xmlhttp.send();
    }

    function display_preoder_sel(tmpcnt, box_id) {
        if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') {
            document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'none';
        } else {
            document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'table-row';
        }

        document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "dashboard_inv_qtyavail.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt, true);
        xmlhttp.send();
    }
    </script>
    <style>
    .popup_qty {
        text-decoration: underline;
        cursor: pointer;
    }

    #loadingDiv {
        position: absolute;
        ;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
    }
    </style>
    <?php
		if (isset($_REQUEST["g_timing"])) {
			$g_timing = $_REQUEST["g_timing"];
		} else {
			$g_timing = $g_timing;
		}
		if (isset($_REQUEST["sort_g_tool"])) {
			$sort_g_tool = $_REQUEST["sort_g_tool"];
		} else {
			$sort_g_tool = $sort_g_tool;
		}
		if (isset($_REQUEST["sort_g_view"])) {
			$sort_g_view = $_REQUEST["sort_g_view"];
		} else {
			$sort_g_view = $sort_g_view;
		}
		?>
    <script language="JavaScript" SRC="inc/CalendarPopup.js"></script>
    <script language="JavaScript" SRC="inc/general.js"></script>
    <script language="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script language="JavaScript">
    var cal1xx = new CalendarPopup("listdiv");
    cal1xx.showNavigationDropdowns();
    </script>
    <script>
    function add_product_fun() {
        var cnt = document.getElementById("prod_cnt").value;
        var chkcondition = document.getElementById("filter_andorcondition" + cnt).value;
        var filtercol = document.getElementById("filter_column" + cnt).value;
        if (filtercol != "-" && chkcondition == "") {
            alert("Please select Condition");
            return false;
        }
        cnt = Number(cnt) + 1;

        var sstr = "";
        sstr = "<table style='font-size:8pt;' id='inv_child_div" + cnt +
            "'><tr><td>Select table column:</td><td><select style='font-size:8pt;' name='filter_column[]' id='filter_column" +
            cnt + "' onChange='showfilter_option(" + cnt +
            ")'><option value=''>Select Option</option><option value='Box Type'>Box Type</option><option value='State'>Location State</option><option value='No. of Wall'>No. of Wall</option><option value='ucbwarehouse'>Warehouse</option><option value='Actual'>Actual</option><option value='After PO'>After PO</option><option value='Last Month Quantity'>Last Month Quantity</option><option value='Availability'>Availability</option><option value='Vendor'>Vendor</option><option value='Ship From'>Ship From</option><option value='Length'>Box Length</option><option value='Width'>Box Width</option><option value='Height'>Box Height</option><option value='Description'>Description</option><option value='SKU'>SKU</option><option value='Per Pallet'>Per Pallet</option><option value='Per Trailer'>Per Trailer</option><option value='Min FOB'>Min FOB</option><option value='Cost'>Cost</option></select></td><td><select style='font-size:8pt;' id='filter_compare_condition" +
            cnt +
            "' name='filter_compare_condition[]'><option value='='>=</option><option value='>'>></option><option value='<'><</option></select></td><td><div id='filter_sub_option" +
            cnt +
            "'><input style='font-size:8pt;' type='input' id='filter_inp' value='' /></div></td><td><select style='font-size:8pt;' id='filter_andorcondition" +
            cnt +
            "' name='filter_andorcondition[]'><option value=''>Select</option><option value='And'>And</option><option value='Or'>Or</option></select><input style='font-size:8pt;' type='button' name='btn_remove' value='X' onclick='remove_product_fun(" +
            cnt + ")'></td></tr></table></div></div>";

        var divctl = document.getElementById("inv_main_div");
        divctl.insertAdjacentHTML('beforeend', sstr);

        document.getElementById("prod_cnt").value = cnt;
    }

    function remove_product_fun(cnt) {

        document.getElementById("inv_child_div" + cnt).innerHTML = "";

        //var cnt = document.getElementById("prod_cnt").value;
        //cnt = Number(cnt) - 1;
        //if (cnt > 0) {
        //document.getElementById("prod_cnt").value = cnt;
        //}
    }


    function showfilter_option(cnt) {
        // 
        var str = document.getElementById("filter_column" + cnt).value;

        if (str.length == 0) {
            //document.getElementById("filter_sub_option").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("filter_sub_option" + cnt).innerHTML = this.responseText;
                }
            };
            xmlhttp.open("POST", "getfilter_sub_options.php?op=" + str + "&cnt=" + cnt, true);
            xmlhttp.send();
        }
    }
    </script>
    <script src="jQuery/jquery-2.1.3.min.js" type="text/javascript"></script>
    <script>
    function dynamic_Select(sort) {
        var skillsSelect = document.getElementById('dropdown');
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
        document.getElementById("temp").value = selectedText;
    }

    function displaynonucbgaylord(colid, sortflg) {
        document.getElementById("div_noninv_gaylord").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_gaylord").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbgaylord.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displayurgentbox(colid, sortflg, cnt) {
        document.getElementById("ug_box").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        //alert(colid);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("ug_box").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displayurgentbox.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displayucbinv(colid, sortflg) {
        document.getElementById("div_ucbinv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_ucbinv").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displayucbinv.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displaynonucbshipping(colid, sortflg) {
        document.getElementById("div_noninv_shipping").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_shipping").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbshipping.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displaynonucbsupersack(colid, sortflg) {
        document.getElementById("div_noninv_supersack").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_supersack").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbsupersack.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displaynonucbdrumBarrel(colid, sortflg) {
        document.getElementById("div_noninv_drumBarrel").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_drumBarrel").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbdrumBarrel.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displaynonucbpallets(colid, sortflg) {
        document.getElementById("div_noninv_pallets").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_pallets").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbpallets.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function sort_Select(warehouseid) {
        var Selectval = document.getElementById('sort_by_order');
        var order_type = Selectval.options[Selectval.selectedIndex].text;


        if (document.getElementById("dropdown").value == "") {
            alert("Please Select the field.");
        } else {
            document.getElementById("tempval_focus").focus();

            document.getElementById("tempval").style.display = "none";
            document.getElementById("tempval1").innerHTML =
                "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    if (order_type != "") {
                        document.getElementById("tempval1").innerHTML = xmlhttp.responseText;
                    }
                }
            }

            xmlhttp.open("GET", "pre_order_sort.php?warehouseid=" + warehouseid + "&selectedgrpid_inedit=" + document
                .getElementById("temp").value + "&sort_order=" + order_type, true);
            xmlhttp.send();
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

    function displayafterpo(boxid) {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("after_pos" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 250;
        n_top = n_top - 100;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_showafterpo.php?id=" + boxid, true);
        xmlhttp.send();
    }

    function displaymap() {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("show_map1");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 50;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_showmap.php", true);
        xmlhttp.send();
    }


    function displayflyer(boxid, flyernm) {
        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" +
            flyernm + "' width='700' height='800'>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("box_fly_div" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 350;
        n_top = n_top - 200;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 'px';

    }

    function displayflyer_main(boxid, flyernm) {
        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" +
            flyernm + "' width='700' height='800'>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("box_fly_div_main" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 350;
        n_top = n_top - 200;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

    }

    function displayactualpallet(boxid) {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("actual_pos" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_top = n_top - 200;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_inventory.php?inventory_id=" + boxid + "&action=run", true);
        xmlhttp.send();
    }

    function displayboxdata(boxid) {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("box_div" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 350;
        n_top = n_top - 200;

        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
        xmlhttp.send();
    }

    function displayboxdata_main(boxid) {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("box_div_main" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 350;
        n_top = n_top - 200;

        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
        xmlhttp.send();
    }

    function display_orders_data(tmpcnt, box_id, wid) {
        if (document.getElementById('inventory_preord_top_u' + tmpcnt).style.display == 'table-row') {
            document.getElementById('inventory_preord_top_u' + tmpcnt).style.display = 'none';
        } else {
            document.getElementById('inventory_preord_top_u' + tmpcnt).style.display = 'table-row';
        }

        document.getElementById("inventory_preord_middle_div_u" + tmpcnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("inventory_preord_middle_div_u" + tmpcnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "gaylordstatus_childtable.php?box_id=" + box_id + "&wid=" + wid + "&tmpcnt=" + tmpcnt,
            true);
        xmlhttp.send();
    }


    function savetranslog(warehouse_id, transid, tmpcnt, box_id) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                alert("Data saved.");
                document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
            }
        }

        logdetail = document.getElementById("trans_notes" + transid + tmpcnt).value;
        opsdate = document.getElementById("ops_delivery_date" + transid + tmpcnt).value;

        xmlhttp.open("GET", "gaylordstatus_savetranslog.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt + "&warehouse_id=" +
            warehouse_id + "&transid=" + transid + "&logdetail=" + logdetail + "&opsdate=" + opsdate, true);
        xmlhttp.send();
    }

    function inv_warehouse_list() {
        var chklist_sel = document.getElementById('inv_warehouse');
        var inv_warehouse = "";
        var opts = [],
            opt;
        len = chklist_sel.options.length;
        for (var i = 0; i < len; i++) {
            opt = chklist_sel.options[i];
            if (opt.selected) {
                inv_warehouse = inv_warehouse + opt.value + ",";
            }
        }

        if (inv_warehouse != "") {
            inv_warehouse = inv_warehouse.substring(0, inv_warehouse.length - 1);
        }

        var opts = [],
            opt;
        var inv_boxtype = "";
        var chklist_sel = document.getElementById('inv_boxtype');
        len = chklist_sel.options.length;
        for (var i = 0; i < len; i++) {
            opt = chklist_sel.options[i];
            if (opt.selected) {
                inv_boxtype = inv_boxtype + opt.value + ",";
            }
        }

        /*var chklist = document.getElementById('inv_boxtype');
        var inv_boxtype = "";
        for (var i =0; i < chklist.length; i++) 
        {
          if (chklist.options[i].selected) 
          { 
        	inv_boxtype = inv_boxtype + chklist.options[i].value + ",";
          }
        }*/

        if (inv_boxtype != "") {
            inv_boxtype = inv_boxtype.substring(0, inv_boxtype.length - 1);
        }

        document.getElementById("tempval_focus").focus();

        document.getElementById("tempval").style.display = "none";
        document.getElementById("tempval1").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tempval1").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inv_warehouse_lst.php?warehouse_id_lst=" + inv_warehouse + "&boxtype_lst=" + inv_boxtype,
            true);
        xmlhttp.send();

    }

    function new_inventory_filter() {
        document.getElementById("new_inv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        //
        var sort_g_view = document.getElementById("sort_g_view").value;
        var sort_g_tool = document.getElementById("sort_g_tool").value;
        var g_timing = document.getElementById("g_timing").value;
        //
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert(xmlhttp.responseText);
                document.getElementById("new_inv").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "display_filter_inventory.php?sort_g_view=" + sort_g_view + "&sort_g_tool=" + sort_g_tool +
            "&g_timing=" + g_timing, true);
        xmlhttp.send();
    }
    </script>
    <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
    </div>

    <a href='dashboardnew_account_pipeline_all.php?show=inventory_cron'>Go Back to Old Inventory Version</a><br />
    <a href='javascript:void();' id='show_map1' onclick="displaymap()">Show Map with Boxes</a><br />
    <a target="_blank" href='report_inbound_inventory_summary.php?warehouse_id=0'>Inbound Inventory Summary</a><br />
    <!--<a target="_blank" href='report_inventory_types.php'>Inventory Report</a><br/>--><br />

    <table cellSpacing="1" cellPadding="1" border="0" width="1200">
        <tr align="middle">
            <td colspan="12" class="style24" style="height: 16px"><strong>INVENTORY NOTES</strong> <a
                    href="updateinventorynotes.php">Edit</a></td>
        </tr>
        <tr vAlign="left">
            <td colspan=12>
                <?php
					$sql = "SELECT * FROM loop_inventory_notes ORDER BY dt DESC LIMIT 0,1";
					db();
					$res = db_query($sql);
					$row = array_shift($res);
					echo $row["notes"];
					?>
            </td>
        </tr>
        <tr align="middle">
            <td colspan="12">
                <img src="images/usa_map_territories.png" width="400px" height="200px" />
            </td>
        </tr>
    </table>
    <table width="1400">
        <tr align="middle">
            <div id="light" class="white_content"></div>
            <div id="fade" class="black_overlay"></div>
            <td colspan="16" class="display_maintitle" style="height: 18px"><strong>Urgent Boxes</strong></td>
        </tr>
        <tr>
            <td colspan="16">
                <?php
					$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory WHERE  inventory.Active LIKE 'A' AND  box_urgent=1 ORDER BY inventory.availability DESC";
					//
					//echo $box_query;
					db_b2b();
					$act_inv_res = db_query($box_query);
					//echo tep_db_num_rows($act_inv_res)."<br>";
					if (tep_db_num_rows($act_inv_res) > 0) {
					?>

                <?php
						while ($inv = array_shift($act_inv_res)) {
							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$minfob = $b2b_fob;
							$b2b_fob = "$" . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2bcost = $b2b_cost;
							$b2b_cost = "$" . number_format($b2b_cost, 2);


							//
							$b2b_notes = $inv["N"];
							$b2b_notes_date = $inv["DT"];
							//
							$bpallet_qty = 0;
							$boxes_per_trailer = 0;
							$box_type = "";
							$loop_id = 0;
							$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type, bwall from loop_boxes where b2b_id=" . $inv["I"];
							//echo $qry_sku."<br>";
							$sku = "";
							db();
							$dt_view_sku = db_query($qry_sku);
							while ($sku_val = array_shift($dt_view_sku)) {
								$loop_id = $sku_val['id'];
								$sku = $sku_val['sku'];
								$bpallet_qty = $sku_val['bpallet_qty'];
								$boxes_per_trailer = $sku_val['boxes_per_trailer'];
								$box_type = $sku_val['type'];
								$box_wall = $sku_val['bwall'];
							}
							if ($inv["location_zip"] != "") {
								if ($inv["availability"] != "-3.5") {
									$inv_id_list .= $inv["I"] . ",";
								}
								//To get the Actual PO, After PO
								$rec_found_box = "n";
								$actual_val = 0;
								$after_po_val = 0;
								$last_month_qty = 0;
								$pallet_val = "";
								$pallet_val_afterpo = "";
								$tmp_noofpallet = 0;
								$ware_house_boxdraw = "";
								$preorder_txt = "";
								$preorder_txt2 = "";
								$box_warehouse_id = 0;
								//
								$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id from loop_boxes where b2b_id=" . $inv["I"];
								db();
								$dt_view = db_query($qry_loc);
								while ($loc_res = array_shift($dt_view)) {
									$box_warehouse_id = $loc_res["box_warehouse_id"];
									$shipfrom_city = '';
									$shipfrom_state = '';
									$shipfrom_zip = '';
									if ($loc_res["box_warehouse_id"] == "238") {
										$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
										$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
										db_b2b();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["shipCity"];
										$shipfrom_state = $loc_row["shipState"];
										$shipfrom_zip = $loc_row["shipZip"];
									} else {

										$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
										$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
										db();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["company_city"];
										$shipfrom_state = $loc_row["company_state"];
										$shipfrom_zip = $loc_row["company_zip"];
									}
								}
								$ship_from  = isset($shipfrom_city) . ", " . isset($shipfrom_state) . " " . isset($shipfrom_zip);
								$ship_from2 = isset($shipfrom_state);
								//Find territory
								//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
								$territory = "";
								$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
								$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
								$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
								$midwest = array('MI', 'OH', 'IN', 'KY');
								$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
								$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
								$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
								$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
								$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
								$canada = array();
								$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
								$territory_sort = 99;
								if (in_array(isset($shipfrom_state), $canada_east, TRUE)) {
									$territory = "Canada East";
									$territory_sort = 1;
								} elseif (in_array(isset($shipfrom_state), $east, TRUE)) {
									$territory = "East";
									$territory_sort = 2;
								} elseif (in_array($shipfrom_state, $south, TRUE)) {
									$territory = "South";
									$territory_sort = 3;
								} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
									$territory = "Midwest";
									$territory_sort = 4;
								} else if (in_array($shipfrom_state, $north_central, TRUE)) {
									$territory = "North Central";
									$territory_sort = 5;
								} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
									$territory = "South Central";
									$territory_sort = 6;
								} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
									$territory = "Canada West";
									$territory_sort = 7;
								} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
									$territory = " Pacific Northwest";
									$territory_sort = 8;
								} elseif (in_array($shipfrom_state, $west, TRUE)) {
									$territory = "West";
									$territory_sort = 9;
								} elseif (in_array($shipfrom_state, $canada, TRUE)) {
									$territory = "Canada";
									$territory_sort = 10;
								} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
									$territory = "Mexico";
									$territory_sort = 11;
								}
								//
								$after_po_val_tmp = 0;
								$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
								db_b2b();
								$dt_view_res_box = db_query($dt_view_qry);
								while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
									$rec_found_box = "y";
									$actual_val = $dt_view_res_box_data["actual"];
									$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
									$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
									//
								}
								if ($rec_found_box == "n") {
									$actual_val = $inv["actual_inventory"];
									$after_po_val = $inv["after_actual_inventory"];
									$last_month_qty = $inv["lastmonthqty"];
								}

								if ($box_warehouse_id == 238) {
									$after_po_val = $inv["after_actual_inventory"];
								} else {
									if ($rec_found_box == "n") {
										$after_po_val = $inv["after_actual_inventory"];
									} else {
										$after_po_val = $after_po_val_tmp;
									}
								}

								$to_show_rec = "y";

								if ($g_timing == 2) {
									$to_show_rec = "";
									if ($after_po_val >= $boxes_per_trailer) {
										$to_show_rec = "y";
									}
								}

								//if ($sort_g_tool == 2){
								//	$to_show_rec = "y";	
								//}

								if ($to_show_rec == "y") {
									//account owner
									if ($inv["vendor_b2b_rescue"] > 0) {

										$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
										$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
										db();
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									}
									//
									$vender_nm = "";
									if ($inv["vendor_b2b_rescue"] != "") {
										$q1 = "SELECT * FROM loop_warehouse where id = " . $inv["vendor_b2b_rescue"];
										db();
										$v_query = db_query($q1);
										while ($v_fetch = array_shift($v_query)) {
											$supplier_id = $v_fetch["b2bid"];
											$vender_nm = getnickname($v_fetch['company_name'], $v_fetch["b2bid"]);
											//$vender_nm = $v_fetch['company_name'];
											db_b2b();
											$com_qry = db_query("select * from companyInfo where ID='" . $v_fetch["b2bid"] . "'");
											$com_row = array_shift($com_qry);
										}
									}
									//
									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"] . " Days";
									}

									if ($after_po_val >= $boxes_per_trailer) {
										if ($inv["lead_time"] == 0) {
											$estimated_next_load = "<font color=green>Now</font>";
										}

										if ($inv["lead_time"] == 1) {
											$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
										}
										if ($inv["lead_time"] > 1) {
											$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
										}
									} else {
										if (($inv["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
											$estimated_next_load = "<font color=red>Never (sell the " . $after_po_val . ")</font>";
										} else {
											// logic changed by Zac
											$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $inv["expected_loads_per_mo"]) * 4) . " Weeks";
										}
									}
									if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0) {
										$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
									}
									//
									if ($inv["expected_loads_per_mo"] == 0) {
										$expected_loads_per_mo = "<font color=red>0</font>";
									} else {
										$expected_loads_per_mo = $inv["expected_loads_per_mo"];
									}
									//
									$b2b_status = $inv["b2b_status"];
									$estimated_next_load = $inv["buy_now_load_can_ship_in"];

									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									db();
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_name = $st_row["box_status"];
									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
									} else {
										$b2bstatuscolor = "";
									}
									//
									if ($inv["box_urgent"] == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_name = "URGENT";
									}
									//
									if ($inv["uniform_mixed_load"] == "Mixed") {
										$blength = $inv["blength_min"] . " - " . $inv["blength_max"];
										$bwidth = $inv["bwidth_min"] . " - " . $inv["bwidth_max"];
										$bdepth = $inv["bheight_min"] . " - " . $inv["bheight_max"];
									} else {
										$blength = $inv["lengthInch"];
										$bwidth = $inv["widthInch"];
										$bdepth = $inv["depthInch"];
									}
									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;
									//
									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;
									$urgent_mgArray = array();
									if ($inv["lengthFraction"] != "") {
										$arr_length = explode("/", $inv["lengthFraction"]);
										if (count($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($inv["widthFraction"] != "") {
										$arr_width = explode("/", $inv["widthFraction"]);
										if (count($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}
									if ($inv["depthFraction"] != "") {
										$arr_depth = explode("/", $inv["depthFraction"]);
										if (count($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}
									//
									/*$miles_from=(int) (6371 * $distC * .621371192);
							if ($miles_from <= 250)
							{	//echo "chk gr <br/>";
								$miles_away_color = "green";
							}
							if ( ($miles_from <= 550) && ($miles_from > 250))
							{	
								$miles_away_color = "#FF9933";
							}
							if (($miles_from > 550) )
							{	
								$miles_away_color = "red";
							}		*/
									//
									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if ($inv["box_urgent"] == 1) {
										$b_urgent = "Yes";
									}
									if ($inv["contracted"] == 1) {
										$contracted = "Yes";
									}
									if ($inv["prepay"] == 1) {
										$prepay = "Yes";
									}
									if ($inv["ship_ltl"] == 1) {
										$ship_ltl = "Yes";
									}
									//$tipStr = "Loops ID#: " . $loop_id . "<br>";
									$tipStr = "<b>Notes:</b> " . $inv["N"] . "<br>";
									if ($inv["DT"] != "0000-00-00") {
										$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($inv["DT"])) . "<br>";
									} else {
										$tipStr .= "<b>Notes Date:</b> <br>";
									}
									$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
									$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
									$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
									$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

									$tipStr .= "<b>Qty Avail:</b> " . $after_po_val . "<br>";
									$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $estimated_next_load . "<br>";
									$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $inv["expected_loads_per_mo"] . "<br>";
									$tipStr .= "<b>B2B Status:</b> " . $b2bstatus_name . "<br>";
									$tipStr .= "<b>Supplier Relationship Owner:</b> " . isset($ownername) . "<br>";
									$tipStr .= "<b>B2B ID#:</b> " . $inv["I"] . "<br>";
									$tipStr .= "<b>Description:</b> " . $inv["description"] . "<br>";
									$tipStr .= "<b>Supplier:</b> " .  $vender_nm . "<br>";
									$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
									$tipStr .= "<b>Territory:</b> " . $territory . "<br>";
									$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty . "<br>";
									$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer . "<br>";
									$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
									$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
									//

									//Get data in array
									$urgent_mgArray[] = array('after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'territory_sort' => $territory_sort);
									//	
								} //end $to_show_rec == "y"
							} //end if ($inv["location_zip"] != "")	
							//
						} //End while $inv
					}
					?>
                <table width="100%" border="0" cellspacing="1" cellpadding="1" class="basic_style">
                    <?php
						$x = 0;
						$boxtype_cnt = 0;
						//

						$MGarray = $urgent_mgArray;
						$MGArraysort_I = array();
						$MGArraysort_II = array();
						$MGArraysort_III = array();
						foreach ($MGarray as $MGArraytmp) {
							$MGArraysort_I[] = $MGArraytmp['territory_sort'];
							$MGArraysort_II[] = $MGArraytmp['vendor_nm'];
							$MGArraysort_III[] = $MGArraytmp['depth'];
						}
						array_multisort($MGArraysort_I, SORT_ASC, $MGArraysort_II, SORT_ASC, $MGArraysort_III, SORT_ASC, $MGarray);
						//
						//print_r($MGarray);
						$total_rec = count($MGarray);
						if ($total_rec > 0) {
							$boxtype_cnt = 0;
						?>
                    <tr>
                        <td class="display_maintitle" align="center">Active Inventory Items - Urgent Boxes</td>
                    </tr>
                    <tr>
                        <td>
                            <div id="ug_box">
                                <table width="100%" cellspacing="1" cellpadding="2">
                                    <tr>
                                        <td class='display_title'>Qty Avail&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(1,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(1,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title' width="80px">Buy Now, Load Can Ship In&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(2,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(2,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(3,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(3,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(4,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(4,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>MIN FOB&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(5,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(5,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>B2B ID&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(6,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(6,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>Territory&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(7,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(7,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>B2B Status&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(8,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(8,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td align="center" class='display_title'>L&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(9,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(9,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td align="center" class='display_title'>x</td>

                                        <td align="center" class='display_title'>W&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(10,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(10,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td align="center" class='display_title'>x</td>

                                        <td align="center" class='display_title'>H&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(11,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(11,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(12,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(12,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>Description&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(13,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(13,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>Supplier&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(14,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(14,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title' width="72px">Ship From&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(15,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(15,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title' width="70px">Rep&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(16,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(16,2,<?php echo isset($box_type_cnt); ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>Sales Team Notes&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(17,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(17,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                        <td class='display_title'>Last Notes Date&nbsp;<a href="javascript:void();"
                                                onclick="displayurgentbox(18,1,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="javascript:void();"
                                                onclick="displayurgentbox(18,2,<?php echo $boxtype_cnt; ?>);"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
                                    </tr>
                                    <?php
											$count_arry = 0;
											$count = 0;
											foreach ($MGarray as $MGArraytmp2) {
												//
												$count = $count + 1;
												if (isset($MGArraytmp2["binv"]) == "nonucb") {
													$binv = "";
												}
												if (isset($MGArraytmp2["binv"]) == "ucbown") {
													$binv = "<b>UCB Owned Inventory </b><br>";
												}
												//
												$tipStr = "<b>Notes:</b> " . $MGArraytmp2["b2b_notes"] . "<br>";
												if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
													$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"])) . "<br>";
												} else {
													$tipStr .= "<b>Notes Date:</b> <br>";
												}
												$tipStr .= "<b>Urgent:</b> " . $MGArraytmp2["b_urgent"] . "<br>";
												$tipStr .= "<b>Contracted:</b> " . $MGArraytmp2["contracted"] . "<br>";
												$tipStr .= "<b>Prepay:</b> " . $MGArraytmp2["prepay"] . "<br>";
												$tipStr .= "<b>Can Ship LTL?</b> " . $MGArraytmp2["ship_ltl"] . "<br>";

												$tipStr .= "<b>Qty Avail:</b> " . $MGArraytmp2["after_po_val"] . "<br>";
												$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $MGArraytmp2["estimated_next_load"] . "<br>";
												$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $MGArraytmp2["expected_loads_per_mo"] . "<br>";
												$tipStr .= "<b>B2B Status:</b> " . $MGArraytmp2["b2bstatus_name"] . "<br>";
												$tipStr .= "<b>Supplier Relationship Owner:</b> " . $MGArraytmp2["ownername"] . "<br>";
												$tipStr .= "<b>B2B ID#:</b> " . $MGArraytmp2["b2bid"] . "<br>";
												$tipStr .= "<b>Description:</b> " . $MGArraytmp2["description"] . "<br>";
												$tipStr .= "<b>Supplier:</b> " .  $MGArraytmp2["vendor_nm"] . "<br>";
												$tipStr .= "<b>Ship From:</b> " . $MGArraytmp2["ship_from"] . "<br>";
												$tipStr .= "<b>Territory:</b> " . $MGArraytmp2["territory"] . "<br>";
												$tipStr .= "<b>Per Pallet:</b> " . $MGArraytmp2["bpallet_qty"] . "<br>";
												$tipStr .= "<b>Per Truckload:</b> " . $MGArraytmp2["boxes_per_trailer"] . "<br>";
												$tipStr .= "<b>Min FOB:</b> " . $MGArraytmp2["b2b_fob"] . "<br>";
												$tipStr .= "<b>B2B Cost:</b> " . $MGArraytmp2["b2b_cost"] . "<br>";
												$tipStr .= isset($binv);
												//
												if (isset($row_cnt) == 0) {
													$display_table_css = "display_table";
													$row_cnt = 1;
												} else {
													$row_cnt = 0;
													$display_table_css = "display_table_alt";
												}
												//
												$loopid = get_loop_box_id($MGArraytmp2["b2bid"]);
												$vendornme = $MGArraytmp2["vendor_nm"];

												//
												$sales_order_qty = 0;
												if ($MGArraytmp2["vendor_b2b_rescue_id"] > 0) {
													$dt_so_item = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
													$dt_so_item .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
													$dt_so_item .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
													$dt_so_item .= " WHERE loop_salesorders.box_id = " . $loopid . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
													db();
													$dt_res_so_item = db_query($dt_so_item);
													while ($so_item_row = array_shift($dt_res_so_item)) {
														if ($so_item_row["sumqty"] > 0) {
															$sales_order_qty = $so_item_row["sumqty"];
														}
													}
												}
												$tmpTDstr = "<tr  >";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
												if ($MGArraytmp2["after_po_val"] < 0) {

													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_orders_data($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
													$tmpTDstr =  $tmpTDstr . "<div";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_orders_data($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else {
													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_orders_data($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												}
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><font color='" . $MGArraytmp2["b2bstatuscolor"] . "'>" . $MGArraytmp2["b2bstatus_name"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . get_loop_box_id($MGArraytmp2["b2bid"]) . "&proc=View&'";
												$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";

												//echo " >" ;
												$tmpTDstr =  $tmpTDstr . " >";

												$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><a target='_blank' href='viewCompany.php?ID=" . $MGArraytmp2["supplier_id"] . "'>" . $MGArraytmp2["vendor_nm"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ownername"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
												if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
													$tmpTDstr =  $tmpTDstr . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"]));
												}
												$tmpTDstr =  $tmpTDstr . "</td>";

												$tmpTDstr =  $tmpTDstr . "</tr>";
												//
												$tmpTDstr =  $tmpTDstr . "<tr id='inventory_preord_top_u" . $count . "' align='middle' style='display:none;'>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td colspan='16'>
										<div id='inventory_preord_middle_div_u" . $count . "'></div>		
								  </td></tr>";
												echo $tmpTDstr;
											}
											?>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td height="10px"></td>
                    </tr>
                    <?php
						}
						?>
                </table>



                <!--End Urgent boxes table-->
                <br>
            </td>
        </tr>
    </table>
    <link rel="stylesheet" type="text/css" href="css/newstylechange.css" />
    <form>
        <table class="basic_style" width="100%" cellspacing="2" cellpadding="2" border="0">
            <tr>
                <td class="display_maintitle">
                    Timing : <select class="basic_style" name="g_timing" id="g_timing">
                        <option value="1" <?php if ($g_timing == "1") {
													echo "selected";
												} ?>>Rdy Now + Presell</option>
                        <option value="2" <?php if ($g_timing == "2") {
													echo "selected";
												} ?>>FTL Rdy Now ONLY</option>
                    </select>
                    &nbsp;&nbsp;
                    Status : <select class="basic_style" name="sort_g_tool" id="sort_g_tool">
                        <option value="1" <?php if ($sort_g_tool == "2") {
													echo "selected";
												} ?>>Available to Sell</option>
                        <option value="2" <?php if ($sort_g_tool == "2") {
													echo "selected";
												} ?>>Available to Sell + Potential to Get</option>
                    </select>
                    &nbsp;&nbsp;
                    View: <select class="basic_style" name="sort_g_view" id="sort_g_view">
                        <option value="1" <?php if ($sort_g_view == "2") {
													echo "selected";
												} ?>>UCB View</option>
                        <option value="2" <?php if ($sort_g_view == "2") {
													echo "selected";
												} ?>>Customer Facing View</option>
                    </select>&nbsp;&nbsp;<input type="button" name="box_filter" id="box_filter" value="Filter"
                        onClick="new_inventory_filter(); return false;">
                </td>
            </tr>
            <?php

				?>
        </table>
    </form>
    <div id="new_inv">
        <?php
			//$main_box_types=array("Gaylord","Shipping Boxes", "Supersacks", "Pallets", "Drums/Barrels/IBCs" );
			if (!isset($_REQUEST["sort"])) {
				$gy = array();
				$sb = array();
				$pal = array();
				$sup = array();
				$dbi = array();
				$recy = array();
				$_SESSION['sortarraygy'] = "";
				$_SESSION['sortarraysb'] = "";
				$_SESSION['sortarraysup'] = "";
				$_SESSION['sortarraydbi'] = "";
				$_SESSION['sortarraypal'] = "";
				$_SESSION['sortarrayrecy'] = "";
				//
				$x = 0;
				$newflg = "no";
				$preordercnt = 1;
				$box_type_str_arr = array("'Gaylord','GaylordUCB', 'PresoldGaylord'", "'Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'", "'PalletsUCB','PalletsnonUCB'", "'SupersackUCB','SupersacknonUCB','Supersacks'", "'DrumBarrelUCB','DrumBarrelnonUCB'");
				$box_type_cnt = 0;
				foreach ($box_type_str_arr as $box_type_str_arr_tmp) {
					//
					$box_type_cnt = $box_type_cnt + 1;

					if ($box_type_cnt == 1) {
						$box_type = "Gaylord";
					}
					if ($box_type_cnt == 2) {
						$box_type = "Shipping Boxes";
					}
					if ($box_type_cnt == 3) {
						$box_type = "Pallets";
					}
					if ($box_type_cnt == 4) {
						$box_type = "Supersacks";
					}
					if ($box_type_cnt == 5) {
						$box_type = "Drums/Barrels/IBCs";
					}
					//
					if ($sort_g_tool == 1) {
						$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (" . $box_type_str_arr_tmp . ")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
					}
					if ($sort_g_tool == 2) {
						$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (" . $box_type_str_arr_tmp . ")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
					}
					//
					//echo $box_query ."<br>";
					db_b2b();
					$act_inv_res = db_query($box_query);
					//echo tep_db_num_rows($act_inv_res)."<br>";
					if (tep_db_num_rows($act_inv_res) > 0) {
			?>

        <?php
						while ($inv = array_shift($act_inv_res)) {
							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$minfob = $b2b_fob;
							$b2b_fob = "$" . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2bcost = $b2b_cost;
							$b2b_cost = "$" . number_format($b2b_cost, 2);


							//
							$b2b_notes = $inv["N"];
							$b2b_notes_date = $inv["DT"];
							//
							$bpallet_qty = 0;
							$boxes_per_trailer = 0;
							$box_type = "";
							$loop_id = 0;
							$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type, bwall from loop_boxes where b2b_id=" . $inv["I"];
							//echo $qry_sku."<br>";
							$sku = "";
							db();
							$dt_view_sku = db_query($qry_sku);
							while ($sku_val = array_shift($dt_view_sku)) {
								$loop_id = $sku_val['id'];
								$sku = $sku_val['sku'];
								$bpallet_qty = $sku_val['bpallet_qty'];
								$boxes_per_trailer = $sku_val['boxes_per_trailer'];
								$box_type = $sku_val['type'];
								$box_wall = $sku_val['bwall'];
							}
							if ($inv["location_zip"] != "") {
								if ($inv["availability"] != "-3.5") {
									$inv_id_list .= $inv["I"] . ",";
								}
								//To get the Actual PO, After PO
								$rec_found_box = "n";
								$actual_val = 0;
								$after_po_val = 0;
								$last_month_qty = 0;
								$pallet_val = "";
								$pallet_val_afterpo = "";
								$tmp_noofpallet = 0;
								$ware_house_boxdraw = "";
								$preorder_txt = "";
								$preorder_txt2 = "";
								$box_warehouse_id = 0;
								//
								$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id from loop_boxes where b2b_id=" . $inv["I"];
								db();
								$dt_view = db_query($qry_loc);
								while ($loc_res = array_shift($dt_view)) {
									$box_warehouse_id = $loc_res["box_warehouse_id"];
									$shipfrom_city = "";
									$shipfrom_state = "";
									$shipfrom_zip = "";
									if ($loc_res["box_warehouse_id"] == "238") {
										$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
										$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
										db_b2b();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["shipCity"];
										$shipfrom_state = $loc_row["shipState"];
										$shipfrom_zip = $loc_row["shipZip"];
									} else {

										$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
										$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
										db();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["company_city"];
										$shipfrom_state = $loc_row["company_state"];
										$shipfrom_zip = $loc_row["company_zip"];
									}
								}
								$ship_from  = isset($shipfrom_city) . ", " . isset($shipfrom_state) . " " . isset($shipfrom_zip);
								$ship_from2 = isset($shipfrom_state);
								//Find territory
								//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
								$territory = "";
								$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
								$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
								$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
								$midwest = array('MI', 'OH', 'IN', 'KY');
								$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
								$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
								$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
								$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
								$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
								$canada = array();
								$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
								$territory_sort = 99;
								if (in_array(isset($shipfrom_state), $canada_east, TRUE)) {
									$territory = "Canada East";
									$territory_sort = 1;
								} elseif (in_array($shipfrom_state, $east, TRUE)) {
									$territory = "East";
									$territory_sort = 2;
								} elseif (in_array($shipfrom_state, $south, TRUE)) {
									$territory = "South";
									$territory_sort = 3;
								} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
									$territory = "Midwest";
									$territory_sort = 4;
								} else if (in_array($shipfrom_state, $north_central, TRUE)) {
									$territory = "North Central";
									$territory_sort = 5;
								} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
									$territory = "South Central";
									$territory_sort = 6;
								} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
									$territory = "Canada West";
									$territory_sort = 7;
								} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
									$territory = " Pacific Northwest";
									$territory_sort = 8;
								} elseif (in_array($shipfrom_state, $west, TRUE)) {
									$territory = "West";
									$territory_sort = 9;
								} elseif (in_array($shipfrom_state, $canada, TRUE)) {
									$territory = "Canada";
									$territory_sort = 10;
								} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
									$territory = "Mexico";
									$territory_sort = 11;
								}
								//
								$after_po_val_tmp = 0;
								$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
								db_b2b();
								$dt_view_res_box = db_query($dt_view_qry);
								while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
									$rec_found_box = "y";
									$actual_val = $dt_view_res_box_data["actual"];
									$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
									$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
									//
								}
								if ($rec_found_box == "n") {
									$actual_val = $inv["actual_inventory"];
									$after_po_val = $inv["after_actual_inventory"];
									$last_month_qty = $inv["lastmonthqty"];
								}

								if ($box_warehouse_id == 238) {
									$after_po_val = $inv["after_actual_inventory"];
								} else {
									if ($rec_found_box == "n") {
										$after_po_val = $inv["after_actual_inventory"];
									} else {
										$after_po_val = $after_po_val_tmp;
									}
								}

								$to_show_rec = "y";

								if ($g_timing == 2) {
									$to_show_rec = "";
									if ($after_po_val >= $boxes_per_trailer) {
										$to_show_rec = "y";
									}
								}

								//if ($sort_g_tool == 2){
								//	$to_show_rec = "y";	
								//}

								if ($to_show_rec == "y") {
									//account owner
									if ($inv["vendor_b2b_rescue"] > 0) {

										$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
										$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
										db();
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									}
									//
									$vender_nm = "";
									if ($inv["vendor_b2b_rescue"] != "") {
										$q1 = "SELECT * FROM loop_warehouse where id = " . $inv["vendor_b2b_rescue"];
										db();
										$v_query = db_query($q1);
										while ($v_fetch = array_shift($v_query)) {
											$supplier_id = $v_fetch["b2bid"];
											$vender_nm = getnickname($v_fetch['company_name'], $v_fetch["b2bid"]);
											//$vender_nm = $v_fetch['company_name'];
											db_b2b();
											$com_qry = db_query("select * from companyInfo where ID='" . $v_fetch["b2bid"] . "'");
											$com_row = array_shift($com_qry);
										}
									}
									//
									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"] . " Days";
									}

									if ($after_po_val >= $boxes_per_trailer) {
										if ($inv["lead_time"] == 0) {
											$estimated_next_load = "<font color=green>Now</font>";
										}

										if ($inv["lead_time"] == 1) {
											$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
										}
										if ($inv["lead_time"] > 1) {
											$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
										}
									} else {
										if (($inv["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
											$estimated_next_load = "<font color=red>Never (sell the " . $after_po_val . ")</font>";
										} else {
											// logic changed by Zac
											$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $inv["expected_loads_per_mo"]) * 4) . " Weeks";
										}
									}
									if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0) {
										$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
									}
									//
									if ($inv["expected_loads_per_mo"] == 0) {
										$expected_loads_per_mo = "<font color=red>0</font>";
									} else {
										$expected_loads_per_mo = $inv["expected_loads_per_mo"];
									}
									//
									$b2b_status = $inv["b2b_status"];

									$estimated_next_load = $inv["buy_now_load_can_ship_in"];

									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									db();
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_name = $st_row["box_status"];
									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
									}
									//
									if ($inv["box_urgent"] == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_name = "URGENT";
									}
									//
									if ($inv["uniform_mixed_load"] == "Mixed") {
										$blength = $inv["blength_min"] . " - " . $inv["blength_max"];
										$bwidth = $inv["bwidth_min"] . " - " . $inv["bwidth_max"];
										$bdepth = $inv["bheight_min"] . " - " . $inv["bheight_max"];
									} else {
										$blength = $inv["lengthInch"];
										$bwidth = $inv["widthInch"];
										$bdepth = $inv["depthInch"];
									}
									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;
									//

									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;

									if ($inv["lengthFraction"] != "") {
										$arr_length = explode("/", $inv["lengthFraction"]);
										if (count($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($inv["widthFraction"] != "") {
										$arr_width = explode("/", $inv["widthFraction"]);
										if (count($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}
									if ($inv["depthFraction"] != "") {
										$arr_depth = explode("/", $inv["depthFraction"]);
										if (count($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}
									//
									/*$miles_from=(int) (6371 * $distC * .621371192);
							if ($miles_from <= 250)
							{	//echo "chk gr <br/>";
								$miles_away_color = "green";
							}
							if ( ($miles_from <= 550) && ($miles_from > 250))
							{	
								$miles_away_color = "#FF9933";
							}
							if (($miles_from > 550) )
							{	
								$miles_away_color = "red";
							}		*/
									//
									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if ($inv["box_urgent"] == 1) {
										$b_urgent = "Yes";
									}
									if ($inv["contracted"] == 1) {
										$contracted = "Yes";
									}
									if ($inv["prepay"] == 1) {
										$prepay = "Yes";
									}
									if ($inv["ship_ltl"] == 1) {
										$ship_ltl = "Yes";
									}
									//$tipStr = "Loops ID#: " . $loop_id . "<br>";
									$tipStr = "<b>Notes:</b> " . $inv["N"] . "<br>";
									if ($inv["DT"] != "0000-00-00") {
										$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($inv["DT"])) . "<br>";
									} else {
										$tipStr .= "<b>Notes Date:</b> <br>";
									}
									$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
									$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
									$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
									$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

									$tipStr .= "<b>Qty Avail:</b> " . $after_po_val . "<br>";
									$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $estimated_next_load . "<br>";
									$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $inv["expected_loads_per_mo"] . "<br>";
									$tipStr .= "<b>B2B Status:</b> " . $b2bstatus_name . "<br>";
									$tipStr .= "<b>Supplier Relationship Owner:</b> " . isset($ownername) . "<br>";
									$tipStr .= "<b>B2B ID#:</b> " . $inv["I"] . "<br>";
									$tipStr .= "<b>Description:</b> " . $inv["description"] . "<br>";
									$tipStr .= "<b>Supplier:</b> " .  $vender_nm . "<br>";
									$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
									$tipStr .= "<b>Territory:</b> " . $territory . "<br>";
									$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty . "<br>";
									$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer . "<br>";
									$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
									$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
									//

									//Get data in array
									if ($box_type_cnt == 1) {
										$gy[] = array('after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 2) {
										$sb[] = array('after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 3) {
										$pal[] = array('after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 4) {
										$sup[] = array('after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 5) {
										$dbi[] = array('after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
									}
									//	
								} //end $to_show_rec == "y"
							} //end if ($inv["location_zip"] != "")	
							//
						} //End while $inv
					} //End check num rows>0

					//Ucbowned
					if ($sort_g_tool == 1) {
						$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
					}
					if ($sort_g_tool == 2) {
						$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) order by warehouse, type_ofbox, Description";
					}
					//echo $dt_view_qry;
					db_b2b();
					$dt_view_res = db_query($dt_view_qry);
					$tmpwarenm = "";
					$tmp_noofpallet = 0;
					$ware_house_boxdraw = "";
					while ($dt_view_row = array_shift($dt_view_res)) {

						$b2bid_tmp = 0;
						$boxes_per_trailer_tmp = 0;
						$bpallet_qty_tmp = 0;
						$vendor_id = 0;
						$vendor_b2b_rescue_id = 0;
						$qry_loopbox = "select b2b_id, boxes_per_trailer, bpallet_qty, vendor, b2b_status, box_warehouse_id, expected_loads_per_mo from loop_boxes where id=" . $dt_view_row["trans_id"];
						db();
						$dt_view_loopbox = db_query($qry_loopbox);
						while ($rs_loopbox = array_shift($dt_view_loopbox)) {
							$b2bid_tmp = $rs_loopbox['b2b_id'];
							$boxes_per_trailer_tmp = $rs_loopbox['boxes_per_trailer'];
							$bpallet_qty_tmp = $rs_loopbox['bpallet_qty'];
							$vendor_id = $rs_loopbox['vendor'];
							$vendor_b2b_rescue_id = $rs_loopbox['box_warehouse_id'];
						}



						$inv_availability = "";
						$distC = 0;
						$inv_notes = "";
						$inv_notes_dt = "";

						$inv_qry = "SELECT * from inventory where ID = " . $b2bid_tmp;
						db_b2b();
						$dt_view_inv_res = db_query($inv_qry);
						while ($dt_view_row_inv = array_shift($dt_view_inv_res)) {
							$inv_notes = $dt_view_row_inv["notes"];
							$inv_notes_dt = $dt_view_row_inv["date"];
							$location_city = $dt_view_row_inv["location_city"];
							$location_state = $dt_view_row_inv["location_state"];
							$location_zip = $dt_view_row_inv["location_zip"];
							$vendor_b2b_rescue = $dt_view_row_inv["vendor_b2b_rescue"];
							$vendor_id = $dt_view_row_inv["vendor"];

							if (isset($inv["lead_time"]) <= 1) {
								$lead_time = "Next Day";
							} else {
								$lead_time = $dt_view_row_inv["lead_time"] . " Days";
							}
							//
							$b2bstatus = $dt_view_row_inv['b2bstatus'];
							$expected_loads_permo = $dt_view_row_inv['expected_loads_permo'];

							//account owner
							if ($vendor_b2b_rescue > 0) {
								$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
								db();
								$query = db_query($q1);
								while ($fetch = array_shift($query)) {
									$supplier_id = $fetch["b2bid"];
									$vender_name = getnickname($fetch['company_name'], $fetch["b2bid"]);
									//
									$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.ID=" . $fetch["b2bid"];
									db_b2b();
									$comres = db_query($comqry);
									while ($comrow = array_shift($comres)) {
										$ownername = $comrow["initials"];
									}
								}
							}


							$tmp_zipval = "";
							$tmppos_1 = strpos($dt_view_row_inv["location_zip"], " ");
							if ($tmppos_1 != false) {
								$tmp_zipval = str_replace(" ", "", $dt_view_row_inv["location_zip"]);
								$zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
							} else {
								$zipStr = "Select * from ZipCodes WHERE zip = '" . intval($dt_view_row_inv["location_zip"]) . "'";
							}
							if ($dt_view_row_inv["location_zip"] != "") {
								db_b2b();
								$dt_view_res3 = db_query($zipStr);
								while ($ziploc = array_shift($dt_view_res3)) {
									$locLat = $ziploc["latitude"];

									$locLong = $ziploc["longitude"];
								}
							}
						}
						$minfob = $dt_view_row["min_fob"];
						$b2bcost = $dt_view_row["b2b_cost"];
						$b2b_fob = "$" . number_format($dt_view_row["min_fob"], 2);
						$b2b_cost = "$" . number_format($dt_view_row["cost"], 2);

						$sales_order_qty = $dt_view_row["sales_order_qty"];

						if (($dt_view_row["actual"] != 0) or ($dt_view_row["actual"] - $sales_order_qty != 0)) {
							$lastmonth_val = $dt_view_row["lastmonthqty"];

							$reccnt = 0;
							if ($sales_order_qty > 0) {
								$reccnt = $sales_order_qty;
							}

							$preorder_txt = "";
							$preorder_txt2 = "";

							if ($reccnt > 0) {
								$preorder_txt = "<u>";
								$preorder_txt2 = "</u>";
							}

							if (($dt_view_row["actual"] >= $boxes_per_trailer_tmp) && ($boxes_per_trailer_tmp > 0)) {
								$bg = "yellow";
							}

							$pallet_val = 0;
							$pallet_val_afterpo = 0;
							$actual_po_tmp = $dt_view_row["actual"] - $sales_order_qty;

							if ($bpallet_qty_tmp > 0) {
								$pallet_val = number_format($dt_view_row["actual"] / $bpallet_qty_tmp, 1, '.', '');
								$pallet_val_afterpo = number_format($actual_po_tmp / $bpallet_qty_tmp, 1, '.', '');
							}

							$to_show_rec1 = "y";

							if ($to_show_rec1 == "y") {
								$pallet_space_per = "";

								if ($pallet_val > 0) {
									$tmppos_1 = strpos($pallet_val, '.');
									if ($tmppos_1 != false) {
										if (intval(substr($pallet_val, strpos($pallet_val, '.') + 1, 1)) > 0) {
											$pallet_val_temp = $pallet_val;
											$pallet_val = " (" . $pallet_val_temp . ")";
										} else {
											$pallet_val_format = number_format($pallet_val, 0);
											$pallet_val = " (" . $pallet_val_format . ")";
										}
									} else {
										$pallet_val_format = number_format($pallet_val, 0);
										$pallet_val = " (" . $pallet_val_format . ")";
									}
								} else {
									$pallet_val = "";
								}

								if ($pallet_val_afterpo > 0) {
									//reg_format = '/^\d+(?:,\d+)*$/';
									$tmppos_1 = strpos($pallet_val_afterpo, '.');
									if ($tmppos_1 != false) {
										if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo, '.') + 1, 1)) > 0) {
											$pallet_val_afterpo_temp = $pallet_val_afterpo;
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
										} else {
											$pallet_val_afterpo_format = number_format($pallet_val_afterpo, 0);
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
										}
									} else {
										$pallet_val_afterpo_format = number_format($pallet_val_afterpo, 0);
										$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
									}
								} else {
									$pallet_val_afterpo = "";
								}
								//
								if ($vendor_b2b_rescue_id == 238) {
									$actual_po = isset($dt_view_row_inv["after_actual_inventory"]);
								} else {
									$actual_po = $actual_po_tmp;
								}
								//
								$to_show_rec = "y";
								if ($g_timing == 2) {
									$to_show_rec = "";
									if ($actual_po >= $boxes_per_trailer_tmp) {
										$to_show_rec = "y";
									}
								}

								//if ($sort_g_tool == 2){
								//	$to_show_rec = "y";	
								//}
								//
								if ($to_show_rec == "y") {

									if ($actual_po >= $boxes_per_trailer_tmp) {
										//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

										if (isset($dt_view_row_inv["lead_time"]) == 0) {
											$estimated_next_load = "<font color=green>Now</font>";
										}

										if (isset($dt_view_row_inv["lead_time"]) == 1) {
											$estimated_next_load = "<font color=green>" . isset($dt_view_row_inv["lead_time"]) . " Day</font>";
										}
										if (isset($dt_view_row_inv["lead_time"]) > 1) {
											$estimated_next_load = "<font color=green>" . isset($dt_view_row_inv["lead_time"]) . " Days</font>";
										}
									} else {
										if ((isset($dt_view_row_inv["expected_loads_per_mo"]) <= 0) && ($actual_po < $boxes_per_trailer_tmp)) {
											$estimated_next_load = "<font color=red>Never (sell the " . $actual_po . ")</font>";
										} else {
											$estimated_next_load = ceil((((($actual_po / $boxes_per_trailer_tmp) * -1) + 1) / isset($dt_view_row_inv["expected_loads_per_mo"])) * 4) . " Weeks";
										}
									}

									if ($actual_po == 0 && isset($dt_view_row_inv["expected_loads_per_mo"]) == 0) {
										$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
									}

									if (isset($dt_view_row_inv["expected_loads_per_mo"]) == 0) {
										$expected_loads_per_mo = "<font color=red>0</font>";
									} else {
										$expected_loads_per_mo = isset($dt_view_row_inv["expected_loads_per_mo"]);
									}

									$estimated_next_load = isset($dt_view_row_inv["buy_now_load_can_ship_in"]);

									$blength = isset($dt_view_row_inv["lengthInch"]);
									$bwidth = isset($dt_view_row_inv["widthInch"]);
									$bdepth = isset($dt_view_row_inv["depthInch"]);
									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;

									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;

									if (isset($dt_view_row_inv["lengthFraction"]) != "") {
										$arr_length = explode("/", isset($dt_view_row_inv["lengthFraction"]));
										if (count($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($dt_view_row_inv["widthFraction"] != "") {
										$arr_width = explode("/", $dt_view_row_inv["widthFraction"]);
										if (count($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}

									if (isset($dt_view_row_inv["depthFraction"]) != "") {
										$arr_depth = isset($dt_view_row_inv["depthFraction"]) ? explode("/", $dt_view_row_inv["depthFraction"]) : [];
										if (count($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}

									//
									$b2b_status = $dt_view_row["b2b_status"];

									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									//echo $st_query;
									db();
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_nametmp = $st_row["box_status"];

									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
									}

									if (isset($dt_view_row_inv["box_urgent"]) == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_nametmp = "URGENT";
									}

									//
									$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue from loop_boxes where b2b_id=" . $dt_view_row["trans_id"];
									db();
									$dt_view = db_query($qry_loc);
									while ($loc_res = array_shift($dt_view)) {
										if ($loc_res["box_warehouse_id"] == "238") {
											$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
											$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
											db_b2b();
											$get_loc_res = db_query($get_loc_qry);
											$loc_row = array_shift($get_loc_res);
											$shipfrom_city = $loc_row["shipCity"];
											$shipfrom_state = $loc_row["shipState"];
											$shipfrom_zip = $loc_row["shipZip"];
										} else {

											$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
											$get_loc_qry = "Select * from loop_warehouse where id = '" . $vendor_b2b_rescue_id . "'";
											db();
											$get_loc_res = db_query($get_loc_qry);
											$loc_row = array_shift($get_loc_res);
											$shipfrom_city = $loc_row["company_city"];
											$shipfrom_state = $loc_row["company_state"];
											$shipfrom_zip = $loc_row["company_zip"];
										}
									}
									$ship_from_tmp  = isset($shipfrom_city) . ", " . isset($shipfrom_state) . " " . isset($shipfrom_zip);
									$ship_from2_tmp = isset($shipfrom_state);
									//
									//Find territory
									//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
									$territory = "";
									$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
									$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
									$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
									$midwest = array('MI', 'OH', 'IN', 'KY');
									$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
									$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
									$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
									$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
									$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
									$canada = array();
									$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
									$territory_sort = 99;
									if (in_array($shipfrom_state, $canada_east, TRUE)) {
										$territory = "Canada East";
										$territory_sort = 1;
									} elseif (in_array($shipfrom_state, $east, TRUE)) {
										$territory = "East";
										$territory_sort = 2;
									} elseif (in_array($shipfrom_state, $south, TRUE)) {
										$territory = "South";
										$territory_sort = 3;
									} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
										$territory = "Midwest";
										$territory_sort = 4;
									} else if (in_array($shipfrom_state, $north_central, TRUE)) {
										$territory = "North Central";
										$territory_sort = 5;
									} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
										$territory = "South Central";
										$territory_sort = 6;
									} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
										$territory = "Canada West";
										$territory_sort = 7;
									} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
										$territory = " Pacific Northwest";
										$territory_sort = 8;
									} elseif (in_array($shipfrom_state, $west, TRUE)) {
										$territory = "West";
										$territory_sort = 9;
									} elseif (in_array($shipfrom_state, $canada, TRUE)) {
										$territory = "Canada";
										$territory_sort = 10;
									} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
										$territory = "Mexico";
										$territory_sort = 11;
									}
									//
									//
									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if (isset($dt_view_row_inv["box_urgent"]) == 1) {
										$b_urgent = "Yes";
									}
									if (isset($dt_view_row_inv["contracted"]) == 1) {
										$contracted = "Yes";
									}
									if (isset($dt_view_row_inv["prepay"]) == 1) {
										$prepay = "Yes";
									}
									if (isset($dt_view_row_inv["ship_ltl"]) == 1) {
										$ship_ltl = "Yes";
									}

									//
									$btemp = str_replace(' ', '', $dt_view_row["LWH"]);
									$boxsize = explode("x", $btemp);
									//Ucb owned data
									//echo $box_type_cnt."<br>";
									if ($box_type_cnt == 1) {
										$gy[] = array('after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => isset($dt_view_row_inv["bwall"]), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 2) {
										$sb[] = array('after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => isset($dt_view_row_inv["bwall"]), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 3) {
										$pal[] = array('after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $dt_view_row_inv["bwall"], 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 4) {
										$sup[] = array('after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => isset($dt_view_row_inv["bwall"]), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 5) {
										$dbi[] = array('after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => isset($dt_view_row_inv["bwall"]), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
									}
									//
									//$pallet_space_per = "";

									//----------------------------------------------------------------
								} //end if ($to_show_rec == "y")
							} //End if ($to_show_rec1 == "y")	

						} //if (($dt_view_row["actual"] != 0) OR ($dt_view_row["actual"] - $sales_order_qty !=0 )
					} //while ($dt_view_row
					$_SESSION['sortarraygy'] = $gy;
					$_SESSION['sortarraysb'] = $sb;
					$_SESSION['sortarraysup'] = $sup;
					$_SESSION['sortarraydbi'] = $dbi;
					$_SESSION['sortarraypal'] = $pal;
					//}									
				} //foreach array loop
			}
			//
			?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" class="basic_style">
            <?php
				$x = 0;
				$boxtype_cnt = 0;
				$sorturl = "dashboardnew_account_pipeline_all.php?show=inventory_new&sort_g_view=" . $sort_g_view . "&sort_g_tool=" . $sort_g_tool . "&g_timing=" . $g_timing;
				$box_name_arr = array('gy', 'sb', 'pal', 'sup', 'dbi');
				foreach ($box_name_arr as $box_name) {
					//
					if ($box_name == "gy") {
						$boxtype = "Gaylord";
						$boxtype_cnt = 1;
					}
					if ($box_name == "sb") {
						$boxtype = "Shipping Boxes";
						$boxtype_cnt = 2;
					}
					if ($box_name == "pal") {
						$boxtype = "Pallets";
						$boxtype_cnt = 3;
					}
					if ($box_name == "sup") {
						$boxtype = "Supersacks";
						$boxtype_cnt = 4;
					}
					if ($box_name == "dbi") {
						$boxtype = "Drums/Barrels/IBCs";
						$boxtype_cnt = 5;
					}

					//
					$MGarray = $_SESSION['sortarray' . $box_name];
					$MGArraysort_I = array();
					$MGArraysort_II = array();
					$MGArraysort_III = array();
					foreach ($MGarray as $MGArraytmp) {
						$MGArraysort_I[] = $MGArraytmp['territory_sort'];
						$MGArraysort_II[] = $MGArraytmp['vendor_nm'];
						$MGArraysort_III[] = $MGArraytmp['depth'];
					}
					//print_r($MGarray)."<br>";
					array_multisort($MGArraysort_I, SORT_ASC, $MGArraysort_II, SORT_ASC, $MGArraysort_III, SORT_ASC, $MGarray);
					//
					//print_r($MGarray);
					$total_rec = count($MGarray);
					if ($total_rec > 0) {

				?>
            <tr>
                <td class="display_maintitle" align="center">Active Inventory Items - <?php echo $boxtype; ?></td>
            </tr>
            <tr>
                <td>
                    <div id="btype<?php echo $boxtype_cnt; ?>">
                        <table width="100%" cellspacing="1" cellpadding="2">
                            <?php if ((isset($sort_g_view)) && ($sort_g_view == "1")) { ?>
                            <tr>
                                <td class='display_title'>Qty Avail&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title' width="80px">Buy Now, Load Can Ship In&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>MIN FOB&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>B2B ID&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Territory&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>B2B Status&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(8,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(8,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td align="center" class='display_title'>L&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td align="center" class='display_title'>x</td>

                                <td align="center" class='display_title'>W&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td align="center" class='display_title'>x</td>

                                <td align="center" class='display_title'>H&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Description&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Supplier&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(14,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(14,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title' width="72px">Ship From&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title' width="70px">Rep&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(16,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(16,2,<?php echo $box_type_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Sales Team Notes&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(17,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(17,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Last Notes Date&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(18,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(18,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
                            </tr>
                            <?php
										}
										if ((isset($sort_g_view)) && ($sort_g_view == "2")) {
										?>
                            <tr>
                                <td class='display_title'>Qty Avail<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title' width="80px">Buy Now, Load Can Ship In&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>FOB Origin Price/Unit&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>B2B ID&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Territory&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td align="center" class='display_title'>L&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td align="center" class='display_title'>x</td>

                                <td align="center" class='display_title'>W&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td align="center" class='display_title'>x</td>

                                <td align="center" class='display_title'>H&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Description&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                                <td class='display_title'>Ship From&nbsp;<a href="javascript:void();"
                                        onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                        href="javascript:void();"
                                        onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);"><img
                                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
                            </tr>

                            <?php
										}
										?>
                            <?php
										$count_arry = 0;
										$count = 0;
										foreach ($MGarray as $MGArraytmp2) {
											//
											$count = $count + 1;
											if ($MGArraytmp2["binv"] == "nonucb") {
												$binv = "";
											}
											if ($MGArraytmp2["binv"] == "ucbown") {
												$binv = "<b>UCB Owned Inventory </b><br>";
											}
											//
											$tipStr = "<b>Notes:</b> " . $MGArraytmp2["b2b_notes"] . "<br>";
											if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
												$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"])) . "<br>";
											} else {
												$tipStr .= "<b>Notes Date:</b> <br>";
											}
											$tipStr .= "<b>Urgent:</b> " . $MGArraytmp2["b_urgent"] . "<br>";
											$tipStr .= "<b>Contracted:</b> " . $MGArraytmp2["contracted"] . "<br>";
											$tipStr .= "<b>Prepay:</b> " . $MGArraytmp2["prepay"] . "<br>";
											$tipStr .= "<b>Can Ship LTL?</b> " . $MGArraytmp2["ship_ltl"] . "<br>";

											$tipStr .= "<b>Qty Avail:</b> " . $MGArraytmp2["after_po_val"] . "<br>";
											$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $MGArraytmp2["estimated_next_load"] . "<br>";
											$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $MGArraytmp2["expected_loads_per_mo"] . "<br>";
											$tipStr .= "<b>B2B Status:</b> " . $MGArraytmp2["b2bstatus_name"] . "<br>";
											$tipStr .= "<b>Supplier Relationship Owner:</b> " . $MGArraytmp2["ownername"] . "<br>";
											$tipStr .= "<b>B2B ID#:</b> " . $MGArraytmp2["b2bid"] . "<br>";
											$tipStr .= "<b>Description:</b> " . $MGArraytmp2["description"] . "<br>";
											$tipStr .= "<b>Supplier:</b> " .  $MGArraytmp2["vendor_nm"] . "<br>";
											$tipStr .= "<b>Ship From:</b> " . $MGArraytmp2["ship_from"] . "<br>";
											$tipStr .= "<b>Territory:</b> " . $MGArraytmp2["territory"] . "<br>";
											$tipStr .= "<b>Per Pallet:</b> " . $MGArraytmp2["bpallet_qty"] . "<br>";
											$tipStr .= "<b>Per Truckload:</b> " . $MGArraytmp2["boxes_per_trailer"] . "<br>";
											$tipStr .= "<b>Min FOB:</b> " . $MGArraytmp2["b2b_fob"] . "<br>";
											$tipStr .= "<b>B2B Cost:</b> " . $MGArraytmp2["b2b_cost"] . "<br>";
											$tipStr .= $binv;
											//
											if ($row_cnt == 0) {
												$display_table_css = "display_table";
												$row_cnt = 1;
											} else {
												$row_cnt = 0;
												$display_table_css = "display_table_alt";
											}
											//
											$loopid = get_loop_box_id($MGArraytmp2["b2bid"]);
											$vendornme = $MGArraytmp2["vendor_nm"];

											//
											$sales_order_qty = 0;
											if ($MGArraytmp2["vendor_b2b_rescue_id"] > 0) {
												$dt_so_item = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
												$dt_so_item .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
												$dt_so_item .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
												$dt_so_item .= " WHERE loop_salesorders.box_id = " . $loopid . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
												db();
												$dt_res_so_item = db_query($dt_so_item);
												while ($so_item_row = array_shift($dt_res_so_item)) {
													if ($so_item_row["sumqty"] > 0) {
														$sales_order_qty = $so_item_row["sumqty"];
													}
												}
											}
											//
											if ((isset($sort_g_view)) && ($sort_g_view == "1")) {
												$tmpTDstr = "<tr  >";

												$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
												if ($MGArraytmp2["after_po_val"] < 0) {

													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
													$tmpTDstr =  $tmpTDstr . "<div";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else {
													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												}
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><font color='" . $MGArraytmp2["b2bstatuscolor"] . "'>" . $MGArraytmp2["b2bstatus_name"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . get_loop_box_id($MGArraytmp2["b2bid"]) . "&proc=View&'";
												$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";

												//echo " >" ;
												$tmpTDstr =  $tmpTDstr . " >";

												$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><a target='_blank' href='viewCompany.php?ID=" . $MGArraytmp2["supplier_id"] . "'>" . $MGArraytmp2["vendor_nm"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ownername"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
												if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
													$tmpTDstr =  $tmpTDstr . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"]));
												}
												$tmpTDstr =  $tmpTDstr . "</td>";

												$tmpTDstr =  $tmpTDstr . "</tr>";
												//
												$tmpTDstr =  $tmpTDstr . "<tr id='inventory_preord_top_" . $count . "' align='middle' style='display:none;'>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td colspan='16'>
										<div id='inventory_preord_middle_div_" . $count . "'></div>		
								  </td></tr>";
											}
											if ((isset($sort_g_view)) && ($sort_g_view == "2")) {
												$tmpTDstr = "<tr  >";

												$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
												if ($MGArraytmp2["after_po_val"] < 0) {
													$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
												} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
													$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
												} else {
													$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
												}
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";

												$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</td>";

												/*$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["vendor_nm"] . "</td>";*/

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from2"] . "</td>";

												//$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

												//$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes_date"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "</tr>";
											}
											echo $tmpTDstr;
										}
										?>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td height="10px"></td>
            </tr>
            <?php
					}
				}
				?>
        </table>
    </div>
    <?php
		//
	}
	//End New match inventory
	//-----------------------------------------------------------------------------
	?>

    <?php
	function showinventory_fordashboard_invnew($warehouseid_selected)
	{

		echo "<script type=\"text/javascript\">";
		echo "function display_preoder() {";
		echo " var totcnt = document.getElementById('inventory_preord_totctl').value;";

		echo " for (var tmpcnt = 1; tmpcnt < totcnt; tmpcnt++) {";
		echo " if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') ";
		echo " { document.getElementById('inventory_preord_top_' + tmpcnt).style.display='none'; } else {";
		echo "  document.getElementById('inventory_preord_top_' + tmpcnt).style.display='table-row'; } ";

		echo " if (document.getElementById('inventory_preord_top2_' + tmpcnt).style.display == 'table-row') ";
		echo " { document.getElementById('inventory_preord_top2_' + tmpcnt).style.display='none'; } else {";
		echo "  document.getElementById('inventory_preord_top2_' + tmpcnt).style.display='table-row'; } ";

		echo " if (document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display == 'table-row') ";
		echo " { document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display='none'; } else {";
		echo "  document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display='table-row'; } ";

		echo " var totcnt_child = document.getElementById('inventory_preord_bottom_hd'+ tmpcnt).value;";

		echo " for (var tmpcnt_n = 1; tmpcnt_n < totcnt_child; tmpcnt_n++) {";
		echo " if (document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display == 'table-row') ";
		echo " { document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display='none'; } else {";
		echo "  document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display='table-row'; } ";
		echo "}";

		echo "}";
		echo "}";

		echo "</script>";
	?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <style type="text/css">
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

    .popup_qty {
        text-decoration: underline;
        cursor: pointer;
    }
    </style>
    <!--New Inventory Search option Jquery-->
    <script>
    function add_product_fun() {
        var cnt = document.getElementById("prod_cnt").value;
        var chkcondition = document.getElementById("filter_andorcondition" + cnt).value;
        var filtercol = document.getElementById("filter_column" + cnt).value;
        if (filtercol != "-" && chkcondition == "") {
            alert("Please select Condition");
            return false;
        }
        cnt = Number(cnt) + 1;

        var sstr = "";
        sstr = "<table style='font-size:8pt;' id='inv_child_div" + cnt +
            "'><tr><td>Select table column:</td><td><select style='font-size:8pt;' name='filter_column[]' id='filter_column" +
            cnt + "' onChange='showfilter_option(" + cnt +
            ")'><option value=''>Select Option</option><option value='Box Type'>Box Type</option><option value='State'>Location State</option><option value='No. of Wall'>No. of Wall</option><option value='ucbwarehouse'>Warehouse</option><option value='Actual'>Actual</option><option value='After PO'>After PO</option><option value='Last Month Quantity'>Last Month Quantity</option><option value='Availability'>Availability</option><option value='Vendor'>Vendor</option><option value='Ship From'>Ship From</option><option value='Length'>Box Length</option><option value='Width'>Box Width</option><option value='Height'>Box Height</option><option value='Description'>Description</option><option value='SKU'>SKU</option><option value='Per Pallet'>Per Pallet</option><option value='Per Trailer'>Per Trailer</option><option value='Min FOB'>Min FOB</option><option value='Cost'>Cost</option></select></td><td><select style='font-size:8pt;' id='filter_compare_condition" +
            cnt +
            "' name='filter_compare_condition[]'><option value='='>=</option><option value='>'>></option><option value='<'><</option></select></td><td><div id='filter_sub_option" +
            cnt +
            "'><input style='font-size:8pt;' type='input' id='filter_inp' value='' /></div></td><td><select style='font-size:8pt;' id='filter_andorcondition" +
            cnt +
            "' name='filter_andorcondition[]'><option value=''>Select</option><option value='And'>And</option><option value='Or'>Or</option></select><input style='font-size:8pt;' type='button' name='btn_remove' value='X' onclick='remove_product_fun(" +
            cnt + ")'></td></tr></table></div></div>";

        var divctl = document.getElementById("inv_main_div");
        divctl.insertAdjacentHTML('beforeend', sstr);

        document.getElementById("prod_cnt").value = cnt;
    }

    function remove_product_fun(cnt) {

        document.getElementById("inv_child_div" + cnt).innerHTML = "";

        //var cnt = document.getElementById("prod_cnt").value;
        //cnt = Number(cnt) - 1;
        //if (cnt > 0) {
        //document.getElementById("prod_cnt").value = cnt;
        //}
    }


    function showfilter_option(cnt) {
        // 
        var str = document.getElementById("filter_column" + cnt).value;

        if (str.length == 0) {
            //document.getElementById("filter_sub_option").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("filter_sub_option" + cnt).innerHTML = this.responseText;
                }
            };
            xmlhttp.open("POST", "getfilter_sub_options.php?op=" + str + "&cnt=" + cnt, true);
            xmlhttp.send();
        }
    }
    </script>
    <script src="jQuery/jquery-2.1.3.min.js" type="text/javascript"></script>
    <script>
    function dynamic_Select(sort) {
        var skillsSelect = document.getElementById('dropdown');
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
        document.getElementById("temp").value = selectedText;
    }

    function displaynonucbgaylord(colid, sortflg) {
        document.getElementById("div_noninv_gaylord").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_gaylord").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbgaylord.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displayurgentbox(colid, sortflg) {
        document.getElementById("div_urgent_box").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        //alert(colid);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_urgent_box").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displayurgentbox.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displayucbinv(colid, sortflg) {
        document.getElementById("div_ucbinv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_ucbinv").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displayucbinv.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displaynonucbshipping(colid, sortflg) {
        document.getElementById("div_noninv_shipping").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_shipping").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbshipping.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displaynonucbsupersack(colid, sortflg) {
        document.getElementById("div_noninv_supersack").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_supersack").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbsupersack.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displaynonucbdrumBarrel(colid, sortflg) {
        document.getElementById("div_noninv_drumBarrel").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_drumBarrel").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbdrumBarrel.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function displaynonucbpallets(colid, sortflg) {
        document.getElementById("div_noninv_pallets").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_noninv_pallets").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displaynonucbpallets.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function sort_Select(warehouseid) {
        var Selectval = document.getElementById('sort_by_order');
        var order_type = Selectval.options[Selectval.selectedIndex].text;


        if (document.getElementById("dropdown").value == "") {
            alert("Please Select the field.");
        } else {
            document.getElementById("tempval_focus").focus();

            document.getElementById("tempval").style.display = "none";
            document.getElementById("tempval1").innerHTML =
                "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    if (order_type != "") {
                        document.getElementById("tempval1").innerHTML = xmlhttp.responseText;
                    }
                }
            }

            xmlhttp.open("GET", "pre_order_sort.php?warehouseid=" + warehouseid + "&selectedgrpid_inedit=" + document
                .getElementById("temp").value + "&sort_order=" + order_type, true);
            xmlhttp.send();
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

    function displayafterpo(boxid) {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("after_pos" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 250;
        n_top = n_top - 100;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_showafterpo.php?id=" + boxid, true);
        xmlhttp.send();
    }

    function displaymap() {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("show_map1");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 50;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_showmap.php", true);
        xmlhttp.send();
    }


    function displayflyer(boxid, flyernm) {
        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" +
            flyernm + "' width='700' height='800'>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("box_fly_div" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 350;
        n_top = n_top - 200;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 'px';

    }

    function displayflyer_main(boxid, flyernm) {
        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" +
            flyernm + "' width='700' height='800'>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("box_fly_div_main" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 350;
        n_top = n_top - 200;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

    }

    function displayactualpallet(boxid) {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("actual_pos" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_top = n_top - 200;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_inventory.php?inventory_id=" + boxid + "&action=run", true);
        xmlhttp.send();
    }

    function displayboxdata(boxid) {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("box_div" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 350;
        n_top = n_top - 200;

        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
        xmlhttp.send();
    }

    function displayboxdata_main(boxid) {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("box_div_main" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_left = n_left - 350;
        n_top = n_top - 200;

        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
        xmlhttp.send();
    }

    function display_orders_data(tmpcnt, box_id, wid) {
        if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') {
            document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'none';
        } else {
            document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'table-row';
        }

        document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "gaylordstatus_childtable.php?box_id=" + box_id + "&wid=" + wid + "&tmpcnt=" + tmpcnt,
            true);
        xmlhttp.send();
    }


    function savetranslog(warehouse_id, transid, tmpcnt, box_id) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                alert("Data saved.");
                document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
            }
        }

        logdetail = document.getElementById("trans_notes" + transid + tmpcnt).value;
        opsdate = document.getElementById("ops_delivery_date" + transid + tmpcnt).value;

        xmlhttp.open("GET", "gaylordstatus_savetranslog.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt + "&warehouse_id=" +
            warehouse_id + "&transid=" + transid + "&logdetail=" + logdetail + "&opsdate=" + opsdate, true);
        xmlhttp.send();
    }

    function inv_warehouse_list() {
        var chklist_sel = document.getElementById('inv_warehouse');
        var inv_warehouse = "";
        var opts = [],
            opt;
        len = chklist_sel.options.length;
        for (var i = 0; i < len; i++) {
            opt = chklist_sel.options[i];
            if (opt.selected) {
                inv_warehouse = inv_warehouse + opt.value + ",";
            }
        }

        if (inv_warehouse != "") {
            inv_warehouse = inv_warehouse.substring(0, inv_warehouse.length - 1);
        }

        var opts = [],
            opt;
        var inv_boxtype = "";
        var chklist_sel = document.getElementById('inv_boxtype');
        len = chklist_sel.options.length;
        for (var i = 0; i < len; i++) {
            opt = chklist_sel.options[i];
            if (opt.selected) {
                inv_boxtype = inv_boxtype + opt.value + ",";
            }
        }

        /*var chklist = document.getElementById('inv_boxtype');
        var inv_boxtype = "";
        for (var i =0; i < chklist.length; i++) 
        {
          if (chklist.options[i].selected) 
          { 
        	inv_boxtype = inv_boxtype + chklist.options[i].value + ",";
          }
        }*/

        if (inv_boxtype != "") {
            inv_boxtype = inv_boxtype.substring(0, inv_boxtype.length - 1);
        }

        document.getElementById("tempval_focus").focus();

        document.getElementById("tempval").style.display = "none";
        document.getElementById("tempval1").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tempval1").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inv_warehouse_lst.php?warehouse_id_lst=" + inv_warehouse + "&boxtype_lst=" + inv_boxtype,
            true);
        xmlhttp.send();

    }
    </script>

    <style>
    .style12_new {
        font-size: x-small;
        font-family: Arial, Helvetica, sans-serif;
    }

    .style12 {
        font-size: x-small;
        font-family: Arial, Helvetica, sans-serif;
    }
    </style>
    <?php

		/////////////////////////////////////////// NEW INVENTORY SALES ORDER VALUES
		?>
    <!--------------------------NEW INVENTORY ---------------------------------------------->
    <script language="JavaScript" SRC="inc/CalendarPopup.js"></script>
    <script language="JavaScript" SRC="inc/general.js"></script>
    <script language="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script language="JavaScript">
    var cal1xx = new CalendarPopup("listdiv");
    cal1xx.showNavigationDropdowns();
    </script>

    <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
    </div>

    <a href='dashboardnew_account_pipeline_all.php?show=inventory'>Go Back to Old Inventory Version</a><br />
    <a href='dashboardnew_account_pipeline_all.php?show=inventory_filter'>Inventory For Sales Rep</a><br />
    <a href='javascript:void();' id='show_map1' onclick="displaymap()">Show Map with Boxes</a><br />
    <a target="_blank" href='report_inbound_inventory_summary.php?warehouse_id=0'>Inbound Inventory Summary</a><br />
    <a target="_blank" href='report_inventory_types.php'>Inventory Report</a><br /><br />

    <table cellSpacing="1" cellPadding="1" border="0" width="1200">
        <tr align="middle">
            <td colspan="12" class="style24" style="height: 16px"><strong>INVENTORY NOTES</strong> <a
                    href="updateinventorynotes.php">Edit</a></td>
        </tr>
        <tr vAlign="left">
            <td colspan=12>
                <?php
					$sql = "SELECT * FROM loop_inventory_notes ORDER BY dt DESC LIMIT 0,1";
					db();
					$res = db_query($sql);
					$row = array_shift($res);
					echo $row["notes"];
					?>
                <br />
            </td>
        </tr>

        <?php

			$no_of_urgent_load = 0;
			$no_of_urgent_load_str = "";
			$no_of_urgent_load_val = 0;
			?>
        <tr>
            <td>
                <table width="1400">
                    <tr align="middle">
                        <div id="light" class="white_content"></div>
                        <div id="fade" class="black_overlay"></div>
                        <td colspan="16" class="style24" style="height: 18px"><strong>Urgent Boxes</strong></td>
                    </tr>
                    <tr>
                        <td colspan="16">
                            <div id="div_urgent_box" name="div_urgent_box">
                                <table cellSpacing="1" cellPadding="1" border="0" width="1200">
                                    <tr vAlign="left">

                                        <td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(2,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(2,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>

                                        <td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(3,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(3,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>

                                        <td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(4,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(4,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>

                                        <td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(16,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(16,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>

                                        <td bgColor="#e4e4e4" class="style12">
                                            <font size=1><b>Account Owner&nbsp;<a href="javascript:void();"
                                                        onclick="displayurgentbox(17,1);"><img src="images/sort_asc.jpg"
                                                            width="5px;" height="10px;"></a>&nbsp;<a
                                                        href="javascript:void();" onclick="displayurgentbox(17,2);"><img
                                                            src="images/sort_desc.jpg" width="5px;"
                                                            height="10px;"></a></b></font>
                                        </td>

                                        <td bgColor="#e4e4e4" class="style12">
                                            <font size=1><b>Supplier&nbsp;<a href="javascript:void();"
                                                        onclick="displayurgentbox(5,1);"><img src="images/sort_asc.jpg"
                                                            width="5px;" height="10px;"></a>&nbsp;<a
                                                        href="javascript:void();" onclick="displayurgentbox(5,2);"><img
                                                            src="images/sort_desc.jpg" width="5px;"
                                                            height="10px;"></a></b></font>
                                        </td>

                                        <td bgColor="#e4e4e4" class="style12">
                                            <font size=1><b>Ship From&nbsp;<a href="javascript:void();"
                                                        onclick="displayurgentbox(15,1);"><img src="images/sort_asc.jpg"
                                                            width="5px;" height="10px;"></a>&nbsp;<a
                                                        href="javascript:void();" onclick="displayurgentbox(15,2);"><img
                                                            src="images/sort_desc.jpg" width="5px;"
                                                            height="10px;"></a></b></font>
                                        </td>

                                        <td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(6,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(6,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></font>
                                        </td>

                                        <td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(7,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(7,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></font>
                                        </td>

                                        <td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(9,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(9,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>

                                        <td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(10,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(10,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>

                                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(11,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(11,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>

                                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(12,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(12,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>

                                        <td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(13,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(13,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>

                                        <td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a
                                                    href="javascript:void();" onclick="displayurgentbox(14,1);"><img
                                                        src="images/sort_asc.jpg" width="5px;"
                                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                    onclick="displayurgentbox(14,2);"><img src="images/sort_desc.jpg"
                                                        width="5px;" height="10px;"></a></b></td>
                                    </tr>
                                    <br>
                                    <!--Urgent Boxes table here-->
                                    <?php
										$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE  inventory.Active LIKE 'A' AND  box_urgent=1 ORDER BY inventory.availability DESC";
										//AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5
										//echo $sql . "<br>";
										db_b2b();
										$dt_view_res = db_query($sql);
										while ($inv = array_shift($dt_view_res)) {

											$vendor_name = "";

											//account owner
											if ($inv["vendor_b2b_rescue"] > 0) {

												$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
												$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
												db();
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											} else {
												$vendor_b2b_rescue = $inv["V"];
												$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
												db_b2b();
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = $fetch["Name"];

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											}

											$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
											db();
											$loop_res = db_query($loopsql);

											$loop = array_shift($loop_res);

											if (isset($x) == 0) {
												$x = 1;
												$bg = "#e4e4e4";
											} else {
												$x = 0;
												$bg = "#f4f4f4";
											}
											$tipStr = "";

											if ($inv["shape_rect"] == "1")
												$tipStr = $tipStr . " Rec ";
											if ($inv["shape_oct"] == "1")
												$tipStr = $tipStr . " Oct ";
											if ($inv["wall_2"] == "1")
												$tipStr = $tipStr . " 2W ";
											if ($inv["wall_3"] == "1")
												$tipStr = $tipStr . " 3W ";
											if ($inv["wall_4"] == "1")
												$tipStr = $tipStr . " 4W ";
											if ($inv["wall_5"] == "1")
												$tipStr = $tipStr . " 5W ";
											//
											if ($inv["top_nolid"] == "1")
												$tipStr = $tipStr . " No Top,";
											if ($inv["top_partial"] == "1")
												$tipStr = $tipStr . " Flange Top, ";
											if ($inv["top_full"] == "1")
												$tipStr = $tipStr . " FFT, ";
											if ($inv["top_hinged"] == "1")
												$tipStr = $tipStr . " Hinge Top, ";
											if ($inv["top_remove"] == "1")
												$tipStr = $tipStr . " Lid Top, ";
											if ($inv["bottom_no"] == "1")
												$tipStr = $tipStr . " No Bottom";
											if ($inv["bottom_partial"] == "1")
												$tipStr = $tipStr . " PB w/o SS";
											if ($inv["bottom_partialsheet"] == "1")
												$tipStr = $tipStr . " PB w/ SS";
											if ($inv["bottom_fullflap"] == "1")
												$tipStr = $tipStr . " FFB";
											if ($inv["bottom_interlocking"] == "1")
												$tipStr = $tipStr . " FB";
											if ($inv["bottom_tray"] == "1")
												$tipStr = $tipStr . " Tray Bottom";
											if ($inv["vents_no"] == "1")
												$tipStr = $tipStr . "";
											if ($inv["vents_yes"] == "1")
												$tipStr = $tipStr . ", Vents";
										?>

                                    <?php
											$b2b_status = "";
											db();
											$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
											while ($so_item_row1 = array_shift($dt_res_so_item1)) {
												$b2b_status = $so_item_row1["box_status"];
											}

											$bpallet_qty = 0;
											$boxes_per_trailer = 0;
											$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
											db();
											$dt_view = db_query($qry);
											while ($sku_val = array_shift($dt_view)) {
												$sku = $sku_val['sku'];
												$bpallet_qty = $sku_val['bpallet_qty'];
												$boxes_per_trailer = $sku_val['boxes_per_trailer'];
											}

											$b2b_ulineDollar = round($inv["ulineDollar"]);
											$b2b_ulineCents = $inv["ulineCents"];
											$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
											$b2b_fob = number_format($b2b_fob, 2);

											$b2b_costDollar = round($inv["costDollar"]);
											$b2b_costCents = $inv["costCents"];
											$b2b_cost = $b2b_costDollar + $b2b_costCents;
											$b2b_cost = number_format($b2b_cost, 2);

											$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
											$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
											$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
											$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
											$sales_order_qty_new = 0;
											db();
											$dt_res_so_item1 = db_query($dt_so_item1);
											while ($so_item_row1 = array_shift($dt_res_so_item1)) {
												if ($so_item_row1["sumqty"] > 0) {
													$sales_order_qty_new = $so_item_row1["sumqty"];
												}
											}


											?>
                                    <tr vAlign="center">
                                        <?php if ($sales_order_qty_new > 0) { ?>
                                        <td bgColor="<?php echo $bg; ?>" class="style12">
                                            <font color='blue' size=1>
                                                <div
                                                    onclick="display_orders_data(<?php echo isset($count_arry); ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
                                                    <u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
                                                </div>
                                            </font>
                                        </td>
                                        <?php } else { ?>
                                        <td bgColor="<?php echo $bg; ?>" class="style12">
                                            <font size=1>
                                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
                                            </font>
                                        </td>
                                        <?php } ?>

                                        <td bgColor="<?php echo $bg; ?>" class="style12">
                                            <font size=1>
                                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?>
                                            </font>
                                        </td>

                                        <td bgColor="<?php echo $bg; ?>" class="style12">
                                            <?php if ($inv["availability"] == "3") echo "<b>"; ?>
                                            <?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
                                            <?php if ($inv["availability"] == "2") echo "Available Now"; ?>
                                            <?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
                                            <?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
                                            <?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
                                            <?php if ($inv["availability"] == "-1") echo "Presell"; ?>
                                            <?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
                                            <?php if ($inv["availability"] == "-3") echo "Potential"; ?>
                                            <?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
                                        </td>

                                        <td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

                                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                                            <font size=1><?php echo isset($ownername); ?></font>
                                        </td>

                                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                                            <font size=1>
                                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
                                            </font>
                                        </td>

                                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                                            <font size=1>
                                                <?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
                                            </font>
                                        </td>

                                        <td bgColor="<?php echo $bg; ?>" class="style12">
                                            <font size=1>
                                                <?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
                                        </td>
                                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                                            <font size=1>
                                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a
                                                    target="_blank"
                                                    href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View'
                                                    id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a>
                                            </font>
                                        </td>

                                        <td bgColor="<?php echo $bg; ?>" class="style12">
                                            <font size=1><?php echo $bpallet_qty; ?></font>
                                        </td>
                                        <td bgColor="<?php echo $bg; ?>" class="style12">
                                            <font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
                                        </td>
                                        <td bgColor="<?php echo $bg; ?>" class="style12">
                                            <font size=1><?php echo "$" . $b2b_fob; ?></font>
                                        </td>
                                        <td bgColor="<?php echo $bg; ?>" class="style12">
                                            <font size=1><?php echo "$" . $b2b_cost; ?></font>
                                        </td>

                                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
                                        </td>
                                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a
                                                href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>'
                                                onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
                                            <?php } ?></td>
                                    </tr>

                                    <?php
											$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $inv['actual_inventory'] . "</font></td>";
											if ($sales_order_qty_new > 0) {
												$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . isset($count_arry) . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
												if ($inv['availability'] == '3') $inv_row .= "<b>";
												$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
											} else {
												$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
												if ($inv['availability'] == '3') $inv_row .= "<b>";
												$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
											}

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' >";
											if ($inv['availability'] == '3') $inv_row .= '<b>';
											if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
											if ($inv['availability'] == '2') $inv_row .= 'Available Now';
											if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
											if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
											if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
											if ($inv['availability'] == '-1') $inv_row .= 'Presell';
											if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
											if ($inv['availability'] == '-3') $inv_row .= 'Potential';
											if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
											$inv_row .= "</td>  ";
											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $vendor_name . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob  . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
											$inv_row .= "<td bgColor='$bg' class='style12left' >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											if ($loop['id'] < 0) {
												$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
											} else {
												$inv_row .= $inv['N'] . "</a>";
											}
											$inv_row .= "</td>";
											$inv_row .= "</tr>";

											/*if ($inv["after_actual_inventory"] > 0){
					if ($inv["availability"] == "3")
					{
						if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0){
							$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
							$no_of_urgent_load_str .= $inv_row;
						}	
					}	
				}	*/

											$no_of_urgent_load = $no_of_urgent_load + 1;
											$no_of_urgent_load_str .= $inv_row;

											$no_of_urgent_load_val = $no_of_urgent_load_val + floor($inv["actual_inventory"] / $boxes_per_trailer);

											//&& ($boxes_per_trailer >= $inv["actual_inventory"])
											if ($inv["actual_inventory"] > 0) {
												if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
													$no_of_full_load = isset($no_of_full_load) + floor($inv["actual_inventory"] / $boxes_per_trailer);
													$no_of_full_load_str .= $inv_row;
												}

												$tot_value_full_load = isset($tot_value_full_load) + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob);
											}

											if ($inv["actual_inventory"] > 0) {
												$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
												$no_of_full_load_str_ucb_inv_str .= $inv_row;
											}

											if ($inv["after_actual_inventory"] > 0) {
												$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
												$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
											}

											if ($inv["actual_inventory"] < 0) {
												$no_of_red_on_page = $no_of_red_on_page + 1;
												$no_of_red_on_page_str .= $inv_row;
											}

											$notes_date = new DateTime($inv["DT"]);
											$curr_date = new DateTime();

											$notes_date_diff = $curr_date->diff($notes_date)->format("%d");
											if ($notes_date_diff > 7) {
												$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
												$no_of_inv_item_note_date_str .= $inv_row;
											}

											?>
                                    <tr id='inventory_preord_top_<?php echo isset($count_arry); ?>' align="middle"
                                        style="display:none;">
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td colspan="14"
                                            style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                                            <div id="inventory_preord_middle_div_<?php echo isset($count_arry); ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <?php
											$count_arry = isset($count_arry) + 1;
										}
										?>

                                </table>
                            </div>
                            <!--End Urgent boxes table-->
                            <br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php $prod_cnt = 1; ?>
        <tr align="middle">
            <td colspan="12" class="style24" style="height: 16px">
                <form action="dashboardnew_account_pipeline_all.php" name="frmnewinventory" id="frmnewinventory">
                    <input type="hidden" value="inventory_cron" name="show" id="show" />
                    <table>
                        <tr>
                            <td>
                                <?php if ($_REQUEST["filter_btn"] == "Apply Filter") {
										$filter_type = $_REQUEST["filter_type"];
										$filter_availability = $_REQUEST["filter_availability"];
										$min_height = $_REQUEST["min_height"];
										$max_height = $_REQUEST["max_height"];

										$min_thickness = $_REQUEST["min_thickness"];

										$min_fob = $_REQUEST["min_fob"];
										$chkterritory = $_REQUEST["chkterritory"];
									?>
                                <table id="inv_child_div" width="300px" style="font-size:8pt;">
                                    <tr>
                                        <td>Type:</td>
                                        <td width="5px;">&nbsp;</td>
                                        <td colspan="3">
                                            <select style="font-size:8pt;" name="filter_type" id="filter_type">
                                                <option value="All">All types</option>
                                                <option value="Gaylords" <?php if ($filter_type == "Gaylords") {
																						echo " selected ";
																					} ?>>Gaylords</option>
                                                <option value="Shipping Boxes" <?php if ($filter_type == "Shipping Boxes") {
																							echo " selected ";
																						} ?>>Shipping Boxes</option>
                                                <option value="Supersacks" <?php if ($filter_type == "Supersacks") {
																						echo " selected ";
																					} ?>>Supersacks</option>
                                                <option value="DrumsBarrelsIBCs" <?php if ($filter_type == "DrumsBarrelsIBCs") {
																								echo " selected ";
																							} ?>>Drums/Barrels/IBCs</option>
                                                <option value="Pallets" <?php if ($filter_type == "Pallets") {
																					echo " selected ";
																				} ?>>Pallets</option>
                                                <option value="Recycling" <?php if ($filter_type == "Recycling") {
																						echo " selected ";
																					} ?>>Recycling</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Availability:</td>
                                        <td width="5px;">&nbsp;</td>
                                        <td colspan="3">
                                            <select style="font-size:8pt;" name="filter_availability"
                                                id="filter_availability">
                                                <option value="All">All</option>
                                                <option value="truckloadonly" <?php if ($_REQUEST["filter_availability"] == "truckloadonly") {
																							echo " selected ";
																						} ?>>>= Truckload Only</option>
                                                <option value="anyavailableboxes" <?php if ($_REQUEST["filter_availability"] == "anyavailableboxes") {
																								echo " selected ";
																							} ?>>Any Available Boxes Only</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Height:</td>
                                        <td width="5px;">&nbsp;</td>
                                        <td>
                                            <input type="input" id="min_height" name="min_height"
                                                value="<?php echo $min_height; ?>" style="font-size:8pt;" />
                                        </td>
                                        <td>
                                            To
                                        </td>
                                        <td>
                                            <input type="input" id="max_height" name="max_height"
                                                value="<?php echo $max_height; ?>" style="font-size:8pt;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Min Thickness:</td>
                                        <td width="5px;">&nbsp;</td>
                                        <td colspan="3">
                                            <select style="font-size:8pt;" name="min_thickness" id="min_thickness">
                                                <option value="Any" <?php if ($min_thickness == "Any") {
																				echo " selected ";
																			} ?>>Any</option>
                                                <option value="2ply" <?php if ($min_thickness == "2ply") {
																					echo " selected ";
																				} ?>>2ply or more</option>
                                                <option value="3ply" <?php if ($min_thickness == "3ply") {
																					echo " selected ";
																				} ?>>3ply or more</option>
                                                <option value="4ply" <?php if ($min_thickness == "4ply") {
																					echo " selected ";
																				} ?>>4ply or more</option>
                                                <option value="5ply" <?php if ($min_thickness == "5ply") {
																					echo " selected ";
																				} ?>>5ply or more</option>
                                                <option value="6ply" <?php if ($min_thickness == "6ply") {
																					echo " selected ";
																				} ?>>6ply or more</option>
                                                <option value="7ply" <?php if ($min_thickness == "7ply") {
																					echo " selected ";
																				} ?>>7ply or more</option>
                                                <option value="8ply" <?php if ($min_thickness == "8ply") {
																					echo " selected ";
																				} ?>>8ply or more</option>
                                                <option value="9ply" <?php if ($min_thickness == "9ply") {
																					echo " selected ";
																				} ?>>9ply or more</option>
                                                <option value="10ply" <?php if ($min_thickness == "10ply") {
																					echo " selected ";
																				} ?>>10ply or more</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Max FOB:</td>
                                        <td width="5px;">$</td>
                                        <td colspan="3">
                                            <input type="input" id="min_fob" name="min_fob"
                                                value="<?php echo $min_fob; ?>" style="font-size:8pt;" />
                                        </td>
                                    </tr>
                                </table>

                                <table width="" style="font-size:8pt;">
                                    <!-- Mexico<input type="checkbox" id="_REQUEST["chkterritory"]" name="_REQUEST["chkterritory"]" value="mexico_reg" <?php if ($_REQUEST["chkterritory"] == "mexico_reg") {
																																									echo " checked ";
																																								} ?>/> -->
                                    <tr>
                                        <td>Territory:</td>
                                        <td width="5px;">&nbsp;</td>
                                        <td colspan="3">
                                            <input type="checkbox" id="chkterritory_canada_east"
                                                name="chkterritory_canada_east" value="canada_east" <?php if ($_REQUEST["chkterritory_canada_east"] == "canada_east") {
																																									echo " checked ";
																																								} ?> />Canada East
                                            <input type="checkbox" id="chkterritoryeast_reg" name="chkterritoryeast_reg"
                                                value="east_reg" <?php if ($_REQUEST["chkterritoryeast_reg"] == "east_reg") {
																																						echo " checked ";
																																					} ?> />East
                                            <input type="checkbox" id="chkterritorysouth_reg"
                                                name="chkterritorysouth_reg" value="south_reg" <?php if ($_REQUEST["chkterritorysouth_reg"] == "south_reg") {
																																							echo " checked ";
																																						} ?> />South
                                            <input type="checkbox" id="chkterritorymidwest_reg"
                                                name="chkterritorymidwest_reg" value="midwest_reg" <?php if ($_REQUEST["chkterritorymidwest_reg"] == "midwest_reg") {
																																								echo " checked ";
																																							} ?> />Midwest
                                            <input type="checkbox" id="chkterritorynorthcenteral_reg"
                                                name="chkterritorynorthcenteral_reg" value="northcenteral_reg" <?php if ($_REQUEST["chkterritorynorthcenteral_reg"] == "northcenteral_reg") {
																																													echo " checked ";
																																												} ?> />North Central
                                            <input type="checkbox" id="chkterritorysouthcenteral_reg"
                                                name="chkterritorysouthcenteral_reg" value="southcenteral_reg" <?php if ($_REQUEST["chkterritorysouthcenteral_reg"] == "southcenteral_reg") {
																																													echo " checked ";
																																												} ?> />South Central

                                            <input type="checkbox" id="chkterritory_canada_west"
                                                name="chkterritory_canada_west" value="canada_west" <?php if ($_REQUEST["chkterritory_canada_west"] == "canada_west") {
																																									echo " checked ";
																																								} ?> />Canada West

                                            <input type="checkbox" id="chkterritorypacific_reg"
                                                name="chkterritorypacific_reg" value="pacific_reg" <?php if ($_REQUEST["chkterritorypacific_reg"] == "pacific_reg") {
																																								echo " checked ";
																																							} ?> />Pacific Northwest
                                            <input type="checkbox" id="chkterritorywestern_reg"
                                                name="chkterritorywestern_reg" value="western_reg" <?php if ($_REQUEST["chkterritorywestern_reg"] == "western_reg") {
																																								echo " checked ";
																																							} ?> />Western

                                            <input type="checkbox" id="chkterritorymexico_reg"
                                                name="chkterritorymexico_reg" value="mexico_reg" <?php if ($_REQUEST["chkterritorymexico_reg"] == "mexico_reg") {
																																							echo " checked ";
																																						} ?> />Mexico

                                            <!-- <input type="checkbox" id="chkterritoryother_reg" name="chkterritoryother_reg" value="other_reg" <?php if ($_REQUEST["chkterritoryother_reg"] == "other_reg") {
																																								echo " checked ";
																																							} ?>/>Other -->

                                            <input type="hidden" name="prod_cnt" id="prod_cnt"
                                                value="<?php echo $prod_cnt; ?>">
                                            <input type="submit" id="filter_btn" name="filter_btn"
                                                style="font-size:8pt;" value="Apply Filter" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td width="5px;">&nbsp;</td>
                                        <td colspan="3">
                                            <a target="_blank" href="gaylordstatus.php">Edit Non-Inventory</a>
                                        </td>
                                    </tr>
                                </table>

                                <?php
										$prod_cnt = 0; ?>
                                <div id="inv_main_div">
                                </div>
                                <?php } else { ?>
                                <div id="inv_main_div">
                                    <table id="inv_child_div" width="300px" style="font-size:8pt;">
                                        <tr>
                                            <td>Type:</td>
                                            <td width="5px;">&nbsp;</td>
                                            <td colspan="3">
                                                <select style="font-size:8pt;" name="filter_type" id="filter_type">
                                                    <option value="All">All types</option>
                                                    <option value="Gaylords">Gaylords</option>
                                                    <option value="Shipping Boxes">Shipping Boxes</option>
                                                    <option value="Supersacks">Supersacks</option>
                                                    <option value="DrumsBarrelsIBCs">Drums/Barrels/IBCs</option>
                                                    <option value="Pallets">Pallets</option>
                                                    <option value="Recycling">Recycling</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Availability:</td>
                                            <td width="5px;">&nbsp;</td>
                                            <td colspan="3">
                                                <select style="font-size:8pt;" name="filter_availability"
                                                    id="filter_availability">
                                                    <option value="All">All</option>
                                                    <option value="truckloadonly">>= Truckload Only</option>
                                                    <option value="anyavailableboxes">Any Available Boxes Only</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Height:</td>
                                            <td width="5px;">&nbsp;</td>
                                            <td>
                                                <input type="input" id="min_height" name="min_height" value="0"
                                                    style="font-size:8pt;" />
                                            </td>
                                            <td>
                                                To
                                            </td>
                                            <td>
                                                <input type="input" id="max_height" name="max_height" value="100"
                                                    style="font-size:8pt;" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Min Thickness:</td>
                                            <td width="5px;">&nbsp;</td>
                                            <td colspan="3">
                                                <select style="font-size:8pt;" name="min_thickness" id="min_thickness">
                                                    <option value="Any">Any</option>
                                                    <option value="2ply">2ply or more</option>
                                                    <option value="3ply">3ply or more</option>
                                                    <option value="4ply">4ply or more</option>
                                                    <option value="5ply">5ply or more</option>
                                                    <option value="6ply">6ply or more</option>
                                                    <option value="7ply">7ply or more</option>
                                                    <option value="8ply">8ply or more</option>
                                                    <option value="9ply">9ply or more</option>
                                                    <option value="10ply">10ply or more</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Max FOB:</td>
                                            <td width="5px;">$</td>
                                            <td colspan="3">
                                                <input type="input" id="min_fob" name="min_fob" value="100.00"
                                                    style="font-size:8pt;" />
                                            </td>
                                        </tr>
                                    </table>

                                    <table width="" style="font-size:8pt;">
                                        <tr>
                                            <td>Territory:</td>
                                            <td width="5px;">&nbsp;</td>
                                            <td colspan="3">
                                                <input type="checkbox" id="chkterritory_canada_east"
                                                    name="chkterritory_canada_east" value="canada_east" checked />Canada
                                                East
                                                <input type="checkbox" id="chkterritoryeast_reg"
                                                    name="chkterritoryeast_reg" value="east_reg" checked />East
                                                <input type="checkbox" id="chkterritorysouth_reg"
                                                    name="chkterritorysouth_reg" value="south_reg" checked />South
                                                <input type="checkbox" id="chkterritorymidwest_reg"
                                                    name="chkterritorymidwest_reg" value="midwest_reg" checked />Midwest
                                                <input type="checkbox" id="chkterritorynorthcenteral_reg"
                                                    name="chkterritorynorthcenteral_reg" value="northcenteral_reg"
                                                    checked />North Central
                                                <input type="checkbox" id="chkterritorysouthcenteral_reg"
                                                    name="chkterritorysouthcenteral_reg" value="southcenteral_reg"
                                                    checked />South Central

                                                <input type="checkbox" id="chkterritory_canada_west"
                                                    name="chkterritory_canada_west" value="canada_west" checked />Canada
                                                West

                                                <input type="checkbox" id="chkterritorypacific_reg"
                                                    name="chkterritorypacific_reg" value="pacific_reg" checked />Pacific
                                                Northwest
                                                <input type="checkbox" id="chkterritorywestern_reg"
                                                    name="chkterritorywestern_reg" value="western_reg" checked />Western

                                                <input type="checkbox" id="chkterritorymexico_reg"
                                                    name="chkterritorymexico_reg" value="mexico_reg" checked />Mexico

                                                <!-- <input type="checkbox" id="chkterritoryother_reg" name="chkterritoryother_reg" value="other_reg" checked />Other -->

                                                <input type="hidden" name="prod_cnt" id="prod_cnt"
                                                    value="<?php echo $prod_cnt; ?>">
                                                <input type="submit" id="filter_btn" name="filter_btn"
                                                    style="font-size:8pt;" value="Apply Filter" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td width="5px;">&nbsp;</td>
                                            <td colspan="3">
                                                <a target="_blank" href="gaylordstatus.php">Edit Non-Inventory</a>
                                            </td>
                                        </tr>
                                    </table>
                        </tr>
                        </div>
                        <?php } ?>
            </td>
        </tr>
    </table>
    </form>
    </td>
    </tr>
    <?php
		//To chk the condition
		if ($_REQUEST["filter_btn"] == "Apply Filter") {
			$BoxType_where = "";
			$State_where = "";
			$no_of_wall_where = "";
			$actual_where = "";
			$after_po_where = "";
			$last_month_qty_where = "";
			$availability_where = "";
			$vendor_where = "";
			$box_length_where = "";
			$box_width_where = "";
			$box_height_where = "";
			$description_where = "";
			$sku_where = "";
			$per_pallet_where = "";
			$per_trailer_where = "";
			$min_FOB_where = "";
			$cost_where = "";

			$BoxType_where_ucbq = "";
			$State_where_ucbq = "";
			$no_of_wall_where_ucbq = "";
			$actual_where_ucbq = "";
			$after_po_where_ucbq = "";
			$last_month_qty_where_ucbq = "";
			$availability_where_ucbq = "";
			$vendor_where_ucbq = "";
			$box_length_where_ucbq = "";
			$box_width_where_ucbq = "";
			$box_height_where_ucbq = "";
			$description_where_ucbq = "";
			$sku_where_ucbq = "";
			$per_pallet_where_ucbq = "";
			$per_trailer_where_ucbq = "";
			$min_FOB_where_ucbq = "";
			$cost_where_ucbq = "";

			$main_new_where_condition = "";

			$filter_type = $_REQUEST["filter_type"];
			$filter_availability = $_REQUEST["filter_availability"];
			$min_height = $_REQUEST["min_height"];
			$max_height = $_REQUEST["max_height"];

			$min_thickness = $_REQUEST["min_thickness"];

			$min_fob = $_REQUEST["min_fob"];
			$chkterritory = $_REQUEST["chkterritory"];

			if ($filter_type == "All") {
				$BoxType_where = "";
				$BoxType_where_ucbq = "";
			}
			if ($filter_type == "Gaylords") {
				$BoxType_where = " and box_type in ('Gaylord', 'GaylordUCB')";
				$BoxType_where_ucbq = " and type_ofbox in ('Gaylord', 'GaylordUCB')";
			}
			if ($filter_type == "Shipping Boxes") {
				$BoxType_where = " and box_type in ('Medium', 'Large', 'Xlarge', 'Box', 'Boxnonucb', 'Presold' )";
				$BoxType_where_ucbq = " and type_ofbox in ('Medium', 'Large', 'Xlarge', 'Box', 'Boxnonucb', 'Presold')";
			}
			if ($filter_type == "Supersacks") {
				$BoxType_where = " and box_type in ('SupersackUCB', 'SupersacknonUCB')";
				$BoxType_where_ucbq = " and type_ofbox in ('SupersackUCB', 'SupersacknonUCB')";
			}
			if ($filter_type == "DrumsBarrelsIBCs") {
				$BoxType_where = " and box_type in ('DrumBarrelUCB', 'DrumBarrelnonUCB')";
				$BoxType_where_ucbq = " and type_ofbox in ('DrumBarrelUCB', 'DrumBarrelnonUCB')";
			}
			if ($filter_type == "Pallets") {
				$BoxType_where = " and box_type in ('PalletsUCB', 'PalletsnonUCB')";
				$BoxType_where_ucbq = " and type_ofbox in ('PalletsUCB', 'PalletsnonUCB')";
			}
			if ($filter_type == "Recycling") {
				$BoxType_where = " and box_type in ('Recycling')";
				$BoxType_where_ucbq = " and type_ofbox in ('Recycling')";
			}

			if ($min_height > 0) {
				$box_height_where .= " and inventory.depthInch >= " . $min_height;
				$box_height_where_ucbq .= " and inventory.depthInch >= " . $min_height;
			}

			if ($max_height > 0) {
				$box_height_where .= " and inventory.depthInch <= " . $max_height;
				$box_height_where_ucbq .= " and inventory.depthInch <= " . $max_height;
			}

			if ($min_thickness == "Any") {
				$box_width_where .= "";
				$box_width_where_ucbq .= "";
			}
			if ($min_thickness == "2ply") {
				$box_width_where .= " and bwall >= 2";
				$box_width_where_ucbq .= " and inventory.bwall >= 2";
			}
			if ($min_thickness == "3ply") {
				$box_width_where .= " and bwall >= 3";
				$box_width_where_ucbq .= " and inventory.bwall >= 3";
			}
			if ($min_thickness == "4ply") {
				$box_width_where .= " and bwall >= 4";
				$box_width_where_ucbq .= " and inventory.bwall >= 4";
			}
			if ($min_thickness == "5ply") {
				$box_width_where .= " and bwall >= 5";
				$box_width_where_ucbq .= " and inventory.bwall >= 5";
			}
			if ($min_thickness == "6ply") {
				$box_width_where .= " and bwall >= 6";
				$box_width_where_ucbq .= " and inventory.bwall >= 6";
			}
			if ($min_thickness == "7ply") {
				$box_width_where .= " and bwall >= 7";
				$box_width_where_ucbq .= " and inventory.bwall >= 7";
			}
			if ($min_thickness == "8ply") {
				$box_width_where .= " and bwall >= 8";
				$box_width_where_ucbq .= " and inventory.bwall >= 8";
			}
			if ($min_thickness == "9ply") {
				$box_width_where .= " and bwall >= 9";
				$box_width_where_ucbq .= " and inventory.bwall >= 9";
			}
			if ($min_thickness == "10ply") {
				$box_width_where .= " and bwall >= 10";
				$box_width_where_ucbq .= " and inventory.bwall >= 10";
			}

			if ($min_fob > 0) {
				$min_FOB_where .= " and (ulineDollar + ulineCents) <= " . $min_fob;
				$min_FOB_where_ucbq .= " and min_fob <= " . $min_fob;
			}

			//$chkterritory
			$State_where1 = "";
			$State_where_ucbq1 = "";
			$State_where2 = "";
			$State_where_ucbq2 = "";
			$State_where3 = "";
			$State_where_ucbq3 = "";
			$State_where4 = "";
			$State_where_ucbq4 = "";
			$State_where5 = "";
			$State_where_ucbq5 = "";
			$State_where6 = "";
			$State_where_ucbq6 = "";
			$State_where7 = "";
			$State_where_ucbq7 = "";
			$State_where8 = "";
			$State_where_ucbq8 = "";
			$State_where9 = "";
			$State_where_ucbq9 = "";
			$State_where10 = "";
			$State_where_ucbq10 = "";
			if ($_REQUEST["chkterritoryeast_reg"] == "east_reg") {
				$State_where1 .= " location_country = 'USA' and location_state in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV','NJ','DC','DE')";
				$State_where_ucbq1 .= " location_country = 'USA' and inventory.location_state in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV','NJ','DC','DE') ";
			}
			if ($_REQUEST["chkterritorysouth_reg"] == "south_reg") {
				$State_where2 .= " location_country = 'USA' and location_state in ('NC','SC','GA','AL','MS','TN','FL')";
				$State_where_ucbq2 .= " location_country = 'USA' and inventory.location_state in ('NC','SC','GA','AL','MS','TN','FL') ";
			}
			if ($_REQUEST["chkterritorymidwest_reg"] == "midwest_reg") {
				$State_where3 .= " location_country = 'USA' and location_state in ('MI','OH','IN','KY')";
				$State_where_ucbq3 .= " location_country = 'USA' and inventory.location_state in ('MI','OH','IN','KY') ";
			}
			if ($_REQUEST["chkterritorynorthcenteral_reg"] == "northcenteral_reg") {
				$State_where4 .= " location_country = 'USA' and location_state in ('ND','SD','NE','MN','IA','IL','WI')";
				$State_where_ucbq4 .= " location_country = 'USA' and inventory.location_state in ('ND','SD','NE','MN','IA','IL','WI') ";
			}
			if ($_REQUEST["chkterritorysouthcenteral_reg"] == "southcenteral_reg") {
				$State_where5 .= " location_country = 'USA' and location_state in ('LA','AR','MO','TX','OK','KS','CO','NM')";
				$State_where_ucbq5 .= " location_country = 'USA' and inventory.location_state in ('LA','AR','MO','TX','OK','KS','CO','NM') ";
			}
			if ($_REQUEST["chkterritorypacific_reg"] == "pacific_reg") {
				$State_where6 .= " location_country = 'USA' and location_state in ('WA','OR','ID','MT','WY','AK')";
				$State_where_ucbq6 .= " location_country = 'USA' and  inventory.location_state in ('WA','OR','ID','MT','WY','AK') ";
			}
			if ($_REQUEST["chkterritorywestern_reg"] == "western_reg") {
				$State_where7 .= " location_country = 'USA' and location_state in ('CA','NV','UT','AZ','HI')";
				$State_where_ucbq7 .= " location_country = 'USA' and inventory.location_state in ('CA','NV','UT','AZ','HI') ";
			}

			if ($_REQUEST["chkterritory_canada_east"] == "canada_east") {
				$State_where8 .= " location_country = 'Canada' and location_state in ('NB', 'NF', 'NS','ON', 'PE', 'QC')";
				$State_where_ucbq8 .= " location_country = 'Canada' and inventory.location_state in ('NB', 'NF', 'NS','ON', 'PE', 'QC') ";
			}
			if ($_REQUEST["chkterritory_canada_west"] == "canada_west") {
				$State_where9 .= " location_country = 'Canada' and location_state in ('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT' )";
				$State_where_ucbq9 .= " location_country = 'Canada' and inventory.location_state in ('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT') ";
			}

			if ($_REQUEST["chkterritorymexico_reg"] == "mexico_reg") {
				$State_where10 .= " location_country = 'Mexico' and location_state in ('AG','BS','CH','CL','CM','CO','CS','DF','DG','GR','GT','HG','JA','ME','MI','MO','NA','NL','OA','PB','QE','QR','SI','SL','SO','TB','TL','TM','VE','ZA') ";
				$State_where_ucbq10 .= " location_country = 'Mexico' and inventory.location_state in ('AG','BS','CH','CL','CM','CO','CS','DF','DG','GR','GT','HG','JA','ME','MI','MO','NA','NL','OA','PB','QE','QR','SI','SL','SO','TB','TL','TM','VE','ZA') ";
			}
			if ($_REQUEST["chkterritoryother_reg"] == "other_reg") {
				//$State_where10 .= " location_state not in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV','NC','SC','GA','AL','MS','TN','MI','OH','IN','KY','ND','SD','NE','MN','IA','IL','WI','LA','AR','MO','TX','OK','KS','CO','NM','WA','OR','ID','MT','WY','AK','CA','NV','UT','AZ','HI','AB','BC','LB','MB','NB','NF','NS','NU','NW','ON','PE','QC','SK','YU') ";
				//$State_where_ucbq10 .= " inventory.location_state not in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV','NC','SC','GA','AL','MS','TN','MI','OH','IN','KY','ND','SD','NE','MN','IA','IL','WI','LA','AR','MO','TX','OK','KS','CO','NM','WA','OR','ID','MT','WY','AK','CA','NV','UT','AZ','HI','AB','BC','LB','MB','NB','NF','NS','NU','NW','ON','PE','QC','SK','YU') ";
			}

			if ($State_where1 != "") {
				$State_where .= $State_where1 . " or ";
			}
			if ($State_where2 != "") {
				$State_where .= $State_where2 . " or ";
			}
			if ($State_where3 != "") {
				$State_where .= $State_where3 . " or ";
			}
			if ($State_where4 != "") {
				$State_where .= $State_where4 . " or ";
			}
			if ($State_where5 != "") {
				$State_where .= $State_where5 . " or ";
			}
			if ($State_where6 != "") {
				$State_where .= $State_where6 . " or ";
			}
			if ($State_where7 != "") {
				$State_where .= $State_where7 . " or ";
			}
			if ($State_where8 != "") {
				$State_where .= $State_where8 . " or ";
			}
			if ($State_where9 != "") {
				$State_where .= $State_where9 . " or ";
			}
			if ($State_where10 != "") {
				$State_where .= $State_where10 . " or ";
			}

			if ($State_where != "") {
				$State_where = substr($State_where, 0, strlen($State_where) - 3);
			}

			$State_where_main = "";
			if (
				$State_where1 != "" || $State_where2 != ""  || $State_where3 != "" || $State_where4 != "" || $State_where5 != ""
				|| $State_where6 != "" || $State_where7 != "" || $State_where8 != "" || $State_where9 != "" || $State_where10 != ""
			) {
				$State_where_main = " and ( " . $State_where . ") ";
			}

			if ($State_where_ucbq1 != "") {
				$State_where_ucbq .= $State_where_ucbq1 . " or ";
			}
			if ($State_where_ucbq2 != "") {
				$State_where_ucbq .= $State_where_ucbq2 . " or ";
			}
			if ($State_where_ucbq3 != "") {
				$State_where_ucbq .= $State_where_ucbq3 . " or ";
			}
			if ($State_where_ucbq4 != "") {
				$State_where_ucbq .= $State_where_ucbq4 . " or ";
			}
			if ($State_where_ucbq5 != "") {
				$State_where_ucbq .= $State_where_ucbq5 . " or ";
			}
			if ($State_where_ucbq6 != "") {
				$State_where_ucbq .= $State_where_ucbq6 . " or ";
			}
			if ($State_where_ucbq7 != "") {
				$State_where_ucbq .= $State_where_ucbq7 . " or ";
			}
			if ($State_where_ucbq8 != "") {
				$State_where_ucbq .= $State_where_ucbq8 . " or ";
			}
			if ($State_where_ucbq9 != "") {
				$State_where_ucbq .= $State_where_ucbq9 . " or ";
			}
			if ($State_where_ucbq10 != "") {
				$State_where_ucbq .= $State_where_ucbq10 . " or ";
			}

			if ($State_where_ucbq != "") {
				$State_where_ucbq = substr($State_where_ucbq, 0, strlen($State_where_ucbq) - 3);
			}

			$State_where_ucbq_main = "";
			if (
				$State_where_ucbq1 != "" || $State_where_ucbq2 != ""  || $State_where_ucbq3 != "" || $State_where_ucbq4 != "" || $State_where_ucbq5 != ""
				|| $State_where_ucbq6 != "" || $State_where_ucbq7 != "" || $State_where_ucbq8 != "" || $State_where_ucbq9 != "" || $State_where_ucbq10 != ""
			) {
				$State_where_ucbq_main = " and ( " . $State_where_ucbq . ") ";
			}

			/*echo $BoxType_where . " " . $State_where . " " . $no_of_wall_where . " " . $actual_where . " " . $after_po_where . " " . $last_month_qty_where . " ";
			echo $availability_where . " " . $vendor_where . " " .  $box_length_where . " " .  $box_width_where . " ";
			echo $box_height_where . " " .  $description_where . " " .  $sku_where . " " .  $per_pallet_where . " " .  $per_trailer_where . " " .  $min_FOB_where . " " .  $cost_where . "<br>";   
			*/
		}
		?>
    <tr align="middle">
        <td colspan="12" style="height: 16px">&nbsp;</td>
    </tr>


    <?php
		$top_head_flg = "no";
		$top_head_flg_output = "no";
		$x = 0;
		$count_arry = 0;

		function removeandor($mainstr_data)
		{
			if (trim(substr($mainstr_data, strlen($mainstr_data) - 5, 6)) == "And)") {
				$mainstr_data = substr($mainstr_data, 0, strlen($mainstr_data) - 5) . ") And ";
			}
			if (trim(substr($mainstr_data, strlen($mainstr_data), -5)) == "Or)") {
				$mainstr_data = substr($mainstr_data, 0, strlen($mainstr_data) - 4) . ") Or ";
			}

			return $mainstr_data;
		}

		//$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.gaylord=1 AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -3.5 ORDER BY inventory.availability DESC, vendors.name ASC";
		$main_qry_and = "";
		if (
			isset($BoxType_where) != "" || isset($State_where) != "" || isset($availability_where) != "" || isset($box_width_where) != "" ||
			isset($box_height_where) != "" || isset($min_FOB_where) != "" || isset($State_where) != ""
		) {
			//$main_qry_and = "and ";
		}

		//$main_new_where_condition = trim($main_qry_and . " " . $BoxType_where . " " . $State_where . " " . $no_of_wall_where . " " . $actual_where . " " . $after_po_where . " " . $last_month_qty_where . " " . $availability_where . " " . $vendor_where . " " . $box_length_where . " " . $box_width_where . " " . $box_height_where . " " . $description_where . " " . $sku_where . " " . $per_pallet_where . " " . $per_trailer_where . " " . $min_FOB_where . " " . $cost_where);
		$main_new_where_condition_sub = isset($BoxType_where) . " " . isset($availability_where) . " " . isset($box_width_where) . " " . isset($box_height_where) . " " . isset($min_FOB_where) . " " . isset($State_where_main);
		$main_new_where_condition = trim($main_qry_and . " " . $main_new_where_condition_sub);

		if (trim(substr($main_new_where_condition, strlen($main_new_where_condition) - 5, 6)) == "And)") {
			$main_new_where_condition = substr($main_new_where_condition, 0, strlen($main_new_where_condition) - 5) . ")";
		}
		if (trim(substr($main_new_where_condition, strlen($main_new_where_condition), -5)) == "Or)") {
			$main_new_where_condition = substr($main_new_where_condition, 0, strlen($main_new_where_condition) - 4) . ")";
		}

		//$main_new_where_condition_ucbq = trim($ucbwarehouse_where_ucbq . " " . $BoxType_where_ucbq . " " . $State_where_ucbq . " " . $no_of_wall_where_ucbq . " " . $actual_where_ucbq . " " . $after_po_where_ucbq . " " . $last_month_qty_where_ucbq . " " . $availability_where_ucbq . " " . $vendor_where_ucbq . " " . $box_length_where_ucbq . " " . $box_width_where_ucbq . " " . $box_height_where_ucbq . " " . $description_where_ucbq . " " . $sku_where_ucbq . " " . $per_pallet_where_ucbq . " " . $per_trailer_where_ucbq . " " . $min_FOB_where_ucbq . " " . $cost_where_ucbq);
		$main_new_where_ucbq_condition_sub = isset($BoxType_where_ucbq) . " " . isset($availability_where_ucbq) . " " . isset($box_width_where_ucbq) . " " . isset($box_height_where_ucbq) . " " . isset($min_FOB_where_ucbq) . " " . isset($State_where_ucbq_main);
		//$main_new_where_condition_ucbq = trim($main_qry_and . " " . $main_new_where_ucbq_condition_sub );
		$main_new_where_condition_ucbq = trim($main_new_where_ucbq_condition_sub);

		if ($main_new_where_condition_ucbq != "") {
			if (trim(substr($main_new_where_condition_ucbq, 0, 3)) == "and") {
				$main_new_where_condition_ucbq = " where " . substr($main_new_where_condition_ucbq, 3);
			} else {
				$main_new_where_condition_ucbq = " where " . $main_new_where_condition_ucbq;
			}
		}
		if (trim(substr($main_new_where_condition_ucbq, strlen($main_new_where_condition_ucbq) - 5, 6)) == "And") {
			$main_new_where_condition_ucbq = substr($main_new_where_condition_ucbq, 0, strlen($main_new_where_condition_ucbq) - 5) . ")";
		}
		if (trim(substr($main_new_where_condition_ucbq, strlen($main_new_where_condition_ucbq), -5)) == "Or") {
			$main_new_where_condition_ucbq = substr($main_new_where_condition_ucbq, 0, strlen($main_new_where_condition_ucbq) - 4)  . ")";
		}

		$no_of_full_load = 0;
		$tot_value_full_load = 0;
		$tot_load_available = 0;
		$tot_load_available_val = 0;
		$no_of_red_on_page = 0;
		$no_of_trans_no_delv_date = 0;
		$no_of_trans_plann_del_pass = 0;
		$no_of_inv_item_note_date = 0;

		$no_of_full_load_str = "";
		$tot_value_full_load_str = "";
		$tot_load_available_str = "";
		$tot_load_available_val_str = "";
		$no_of_red_on_page_str = "";
		$no_of_trans_no_delv_date_str = "";
		$no_of_trans_plann_del_pass_str = "";
		$no_of_inv_item_note_date_str = "";

		$no_of_full_load_str_ucb_inv = 0;
		$no_of_full_load_str_ucb_inv_str = "";
		$no_of_full_load_str_ucb_inv_av = "";
		$no_of_full_load_str_ucb_inv_av_str = "";

		//INNER JOIN vendors ON inventory.vendor = vendors.id
		$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.gaylord=1 AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
		//echo $sql . "<br>";
		db_b2b();
		$dt_view_res = db_query($sql);

		while ($inv = array_shift($dt_view_res)) {
			$vendor_name = "";
			//account owner
			if ($inv["vendor_b2b_rescue"] > 0) {

				$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
				$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
				db();
				$query = db_query($q1);
				while ($fetch = array_shift($query)) {
					$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

					$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
					db_b2b();
					$comres = db_query($comqry);
					while ($comrow = array_shift($comres)) {
						$ownername = $comrow["initials"];
					}
				}
			} else {
				$vendor_b2b_rescue = $inv["V"];
				$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
				db_b2b();
				$query = db_query($q1);
				while ($fetch = array_shift($query)) {
					$vendor_name = $fetch["Name"];

					$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
					db_b2b();
					$comres = db_query($comqry);
					while ($comrow = array_shift($comres)) {
						$ownername = $comrow["initials"];
					}
				}
			}
			//
			$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
			db();
			$loop_res = db_query($loopsql);

			$loop = array_shift($loop_res);

			if ($x == 0) {
				$x = 1;
				$bg = "#e4e4e4";
			} else {
				$x = 0;
				$bg = "#f4f4f4";
			}
			$tipStr = "";

			if ($inv["shape_rect"] == "1")

				$tipStr = $tipStr . " Rec ";



			if ($inv["shape_oct"] == "1")

				$tipStr = $tipStr . " Oct ";



			if ($inv["wall_2"] == "1")

				$tipStr = $tipStr . " 2W ";



			if ($inv["wall_3"] == "1")

				$tipStr = $tipStr . " 3W ";



			if ($inv["wall_4"] == "1")

				$tipStr = $tipStr . " 4W ";



			if ($inv["wall_5"] == "1")

				$tipStr = $tipStr . " 5W ";



			if ($inv["top_nolid"] == "1")

				$tipStr = $tipStr . " No Top,";



			if ($inv["top_partial"] == "1")

				$tipStr = $tipStr . " Flange Top, ";



			if ($inv["top_full"] == "1")

				$tipStr = $tipStr . " FFT, ";



			if ($inv["top_hinged"] == "1")

				$tipStr = $tipStr . " Hinge Top, ";



			if ($inv["top_remove"] == "1")

				$tipStr = $tipStr . " Lid Top, ";



			if ($inv["bottom_no"] == "1")

				$tipStr = $tipStr . " No Bottom";



			if ($inv["bottom_partial"] == "1")

				$tipStr = $tipStr . " PB w/o SS";



			if ($inv["bottom_partialsheet"] == "1")

				$tipStr = $tipStr . " PB w/ SS";



			if ($inv["bottom_fullflap"] == "1")

				$tipStr = $tipStr . " FFB";



			if ($inv["bottom_interlocking"] == "1")

				$tipStr = $tipStr . " FB";



			if ($inv["bottom_tray"] == "1")

				$tipStr = $tipStr . " Tray Bottom";



			if ($inv["vents_no"] == "1")

				$tipStr = $tipStr . "";



			if ($inv["vents_yes"] == "1")

				$tipStr = $tipStr . ", Vents";



		?>

    <?php
			$bpallet_qty = 0;
			$boxes_per_trailer = 0;
			$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
			db();
			$dt_view = db_query($qry);
			while ($sku_val = array_shift($dt_view)) {
				$sku = $sku_val['sku'];
				$bpallet_qty = $sku_val['bpallet_qty'];
				$boxes_per_trailer = $sku_val['boxes_per_trailer'];
			}

			$b2b_ulineDollar = round($inv["ulineDollar"]);
			$b2b_ulineCents = $inv["ulineCents"];
			$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
			$b2b_fob = number_format($b2b_fob, 2);

			$b2b_costDollar = round($inv["costDollar"]);
			$b2b_costCents = $inv["costCents"];
			$b2b_cost = $b2b_costDollar + $b2b_costCents;
			$b2b_cost = number_format($b2b_cost, 2);

			$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
			$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
			$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
			$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
			$sales_order_qty_new = 0;
			db();
			$dt_res_so_item1 = db_query($dt_so_item1);
			while ($so_item_row1 = array_shift($dt_res_so_item1)) {
				if ($so_item_row1["sumqty"] > 0) {
					$sales_order_qty_new = $so_item_row1["sumqty"];
				}
			}

			$b2b_status = "";
			db();
			$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
			while ($so_item_row1 = array_shift($dt_res_so_item1)) {
				$b2b_status = $so_item_row1["box_status"];
			}

			$to_show_data = "no";
			if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
				if (isset($filter_availability) == "truckloadonly") {
					if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
						$to_show_data = "yes";
					}
				}
				if (isset($filter_availability) == "anyavailableboxes") {
					if ($inv["after_actual_inventory"] > 0) {
						$to_show_data = "yes";
					}
				}
			} else {
				$to_show_data = "yes";
			}

			if ($to_show_data == "yes") {
				$top_head_flg_output = "yes";
				if ($top_head_flg == "no") {

					$top_head_flg = "yes";
			?>

    <tr align="middle">
        <div id="light" class="white_content"></div>
        <div id="fade" class="black_overlay"></div>
        <td colspan="12" class="style24" style="height: 16px"><strong>Non-UCB Gaylord Totes Inventory</strong></td>
    </tr>

    <tr>
        <td colspan="14">
            <div id="div_noninv_gaylord" name="div_noninv_gaylord">
                <table cellSpacing="1" cellPadding="1" border="0" width="1200">
                    <tr vAlign="left">

                        <td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(2,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(2,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(3,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(3,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(4,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(4,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(16,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(16,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Account Owner&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbgaylord(17,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbgaylord(17,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Supplier&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbgaylord(5,1);"><img src="images/sort_asc.jpg" width="5px;"
                                            height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbgaylord(5,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Ship From&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbgaylord(15,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbgaylord(15,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(6,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(6,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(7,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(7,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(9,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(9,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(10,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(10,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(11,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(11,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(12,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(12,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(13,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(13,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(14,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbgaylord(14,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>
                    </tr>
                    <?php } ?>


                    <tr vAlign="center">

                        <?php if ($sales_order_qty_new > 0) { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font color='blue' size=1>
                                <div
                                    onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
                                    <u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
                                </div>
                            </font>
                        </td>
                        <?php } else { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
                            </font>
                        </td>
                        <?php } ?>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?>
                            <?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
                            <?php if ($inv["availability"] == "2") echo "Available Now"; ?>
                            <?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
                            <?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
                            <?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
                            <?php if ($inv["availability"] == "-1") echo "Presell"; ?>
                            <?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
                            <?php if ($inv["availability"] == "-3") echo "Potential"; ?>
                            <?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1><?php echo isset($ownername); ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a
                                    target="_blank"
                                    href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View'
                                    id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $bpallet_qty; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo "$" . $b2b_fob; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo "$" . $b2b_cost; ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a
                                href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>'
                                onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
                            <?php } ?></td>
                    </tr>

                    <?php
								$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= $inv['actual_inventory'] . "</font></td>";
								if ($sales_order_qty_new > 0) {
									$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
									if ($inv['availability'] == '3') $inv_row .= "<b>";
									$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
								} else {
									$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
									if ($inv['availability'] == '3') $inv_row .= "<b>";
									$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
								}

								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12' >";
								if ($inv['availability'] == '3') $inv_row .= '<b>';
								if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
								if ($inv['availability'] == '2') $inv_row .= 'Available Now';
								if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
								if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
								if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
								if ($inv['availability'] == '-1') $inv_row .= 'Presell';
								if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
								if ($inv['availability'] == '-3') $inv_row .= 'Potential';
								if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
								$inv_row .= "</td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
								$inv_row .= isset($ownername) . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= $vendor_name . "</a></font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob  . "</font></td>";
								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' >";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
								$inv_row .= "<td bgColor='$bg' class='style12left' >";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								if ($loop['id'] < 0) {
									$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
								} else {
									$inv_row .= $inv['N'] . "</a>";
								}
								$inv_row .= "</td>";
								$inv_row .= "</tr>";

								if ($inv["after_actual_inventory"] > 0) {
									if ($inv["availability"] == "3") {
										if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
											//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
											//$no_of_urgent_load_str .= $inv_row;
										}
									}
								}

								//&& ($boxes_per_trailer >= $inv["actual_inventory"])
								if ($inv["actual_inventory"] > 0) {
									if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
										$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
										$no_of_full_load_str .= $inv_row;
									}

									$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob);
								}

								if ($inv["actual_inventory"] > 0) {
									$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
									$no_of_full_load_str_ucb_inv_str .= $inv_row;
								}

								if ($inv["after_actual_inventory"] > 0) {
									$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
									$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
								}

								//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
								if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
									if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
										if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
											$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
											$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob;

											$tot_load_available_str .= $inv_row;
										}
									}
								}

								if ($inv["actual_inventory"] < 0) {
									$no_of_red_on_page = $no_of_red_on_page + 1;
									$no_of_red_on_page_str .= $inv_row;
								}

								$notes_date = new DateTime($inv["DT"]);
								$curr_date = new DateTime();

								$notes_date_diff = $curr_date->diff($notes_date)->format("%d");
								if ($notes_date_diff > 7) {
									$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
									$no_of_inv_item_note_date_str .= $inv_row;
								}

								?>
                    <tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="14"
                            style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                            <div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
                        </td>
                    </tr>

                    <?php
							$count_arry = $count_arry + 1;
						}
					}

					if ($top_head_flg_output == "yes") {
							?>
                </table>
            </div>
        </td>
    </tr>

    <tr align="middle">
        <td>&nbsp;</td>
    </tr>
    <?php } ?>

    <?php $top_head_flg = "no";
				$top_head_flg_output = "no"; ?>

    <?php
				$x = 0;


				$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.box_type = 'Boxnonucb' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
				//echo $sql . "<br>";
				db_b2b();
				$dt_view_res = db_query($sql);

				while ($inv = array_shift($dt_view_res)) {
					$vendor_name = "";
					//account owner
					if ($inv["vendor_b2b_rescue"] > 0) {

						$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
						$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
						db();
						$query = db_query($q1);
						while ($fetch = array_shift($query)) {
							$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

							$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
							db_b2b();
							$comres = db_query($comqry);
							while ($comrow = array_shift($comres)) {
								$ownername = $comrow["initials"];
							}
						}
					} else {
						$vendor_b2b_rescue = $inv["V"];
						$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
						db_b2b();
						$query = db_query($q1);
						while ($fetch = array_shift($query)) {
							$vendor_name = $fetch["Name"];

							$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
							db_b2b();
							$comres = db_query($comqry);
							while ($comrow = array_shift($comres)) {
								$ownername = $comrow["initials"];
							}
						}
					}
					//

					$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
					db();
					$loop_res = db_query($loopsql);

					$loop = array_shift($loop_res);

					if ($x == 0) {
						$x = 1;
						$bg = "#e4e4e4";
					} else {
						$x = 0;
						$bg = "#f4f4f4";
					}
					$tipStr = "";

					if ($inv["shape_rect"] == "1")

						$tipStr = $tipStr . " Rec ";



					if ($inv["shape_oct"] == "1")

						$tipStr = $tipStr . " Oct ";



					if ($inv["wall_2"] == "1")

						$tipStr = $tipStr . " 2W ";



					if ($inv["wall_3"] == "1")

						$tipStr = $tipStr . " 3W ";



					if ($inv["wall_4"] == "1")

						$tipStr = $tipStr . " 4W ";



					if ($inv["wall_5"] == "1")

						$tipStr = $tipStr . " 5W ";



					if ($inv["top_nolid"] == "1")

						$tipStr = $tipStr . " No Top,";



					if ($inv["top_partial"] == "1")

						$tipStr = $tipStr . " Flange Top, ";



					if ($inv["top_full"] == "1")

						$tipStr = $tipStr . " FFT, ";



					if ($inv["top_hinged"] == "1")

						$tipStr = $tipStr . " Hinge Top, ";



					if ($inv["top_remove"] == "1")

						$tipStr = $tipStr . " Lid Top, ";



					if ($inv["bottom_no"] == "1")

						$tipStr = $tipStr . " No Bottom";



					if ($inv["bottom_partial"] == "1")

						$tipStr = $tipStr . " PB w/o SS";



					if ($inv["bottom_partialsheet"] == "1")

						$tipStr = $tipStr . " PB w/ SS";



					if ($inv["bottom_fullflap"] == "1")

						$tipStr = $tipStr . " FFB";



					if ($inv["bottom_interlocking"] == "1")

						$tipStr = $tipStr . " FB";



					if ($inv["bottom_tray"] == "1")

						$tipStr = $tipStr . " Tray Bottom";



					if ($inv["vents_no"] == "1")

						$tipStr = $tipStr . "";



					if ($inv["vents_yes"] == "1")

						$tipStr = $tipStr . ", Vents";


				?>

    <?php
					$bpallet_qty = 0;
					$boxes_per_trailer = 0;
					$work_as_kit_box = 0;
					$qry = "select sku, bpallet_qty, boxes_per_trailer,work_as_kit_box  from loop_boxes where id=" . $loop["id"];
					db();
					$dt_view = db_query($qry);
					while ($sku_val = array_shift($dt_view)) {
						$sku = $sku_val['sku'];
						$bpallet_qty = $sku_val['bpallet_qty'];
						$boxes_per_trailer = $sku_val['boxes_per_trailer'];
						$work_as_kit_box = $sku_val['work_as_kit_box'];
					}

					$b2b_ulineDollar = round($inv["ulineDollar"]);
					$b2b_ulineCents = $inv["ulineCents"];
					$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
					$b2b_fob = "$ " . number_format($b2b_fob, 2);

					$b2b_costDollar = round($inv["costDollar"]);
					$b2b_costCents = $inv["costCents"];
					$b2b_cost = $b2b_costDollar + $b2b_costCents;
					$b2b_cost = "$ " . number_format($b2b_cost, 2);

					$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
					$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
					$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
					$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
					$sales_order_qty_new = 0;
					db();
					$dt_res_so_item1 = db_query($dt_so_item1);
					while ($so_item_row1 = array_shift($dt_res_so_item1)) {
						if ($so_item_row1["sumqty"] > 0) {
							$sales_order_qty_new = $so_item_row1["sumqty"];
						}
					}

					$to_show_data = "no";
					if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
						if (isset($filter_availability) == "truckloadonly") {
							if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
								$to_show_data = "yes";
							}
						}
						if (isset($filter_availability) == "anyavailableboxes") {
							if ($inv["after_actual_inventory"] > 0) {
								$to_show_data = "yes";
							}
						}
					} else {
						$to_show_data = "yes";
					}

					if ($to_show_data == "yes") {

						$b2b_status = "";
						db();
						$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
						while ($so_item_row1 = array_shift($dt_res_so_item1)) {
							$b2b_status = $so_item_row1["box_status"];
						}

						$top_head_flg_output = "yes";
						if ($top_head_flg == "no") {

							$top_head_flg = "yes";
					?>
    <tr align="middle">
        <td colspan="13" class="style24" style="height: 16px"><strong>Non-UCB Shipping Boxes Inventory</strong></td>
    </tr>
    <tr>
        <td colspan="13">
            <div id="div_noninv_shipping" name="div_noninv_shipping">
                <table cellSpacing="1" cellPadding="1" border="0" width="1200">
                    <tr vAlign="left">


                        <td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(2,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(2,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(3,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(3,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(4,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(4,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(16,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(16,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Account Owner&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbshipping(17,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbshipping(17,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Supplier&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbshipping(5,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbshipping(5,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Ship From&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbshipping(15,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbshipping(15,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Work as a Kit box?&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbshipping(16,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbshipping(16,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(6,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(6,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(7,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(7,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(9,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(9,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(10,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(10,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(11,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(11,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(12,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(12,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(13,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(13,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(14,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbshipping(14,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>
                    </tr>

                    <tr vAlign="left">
                        <td colspan=15>
                            <?php } ?>

                    <tr vAlign="center">

                        <?php if ($sales_order_qty_new > 0) { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font color='blue' size=1>
                                <div
                                    onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
                                    <u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
                                </div>
                            </font>
                        </td>
                        <?php } else { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
                            </font>
                        </td>
                        <?php } ?>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?>
                            <?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
                            <?php if ($inv["availability"] == "2") echo "Available Now"; ?>
                            <?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
                            <?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
                            <?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
                            <?php if ($inv["availability"] == "-1") echo "Presell"; ?>
                            <?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
                            <?php if ($inv["availability"] == "-3") echo "Potential"; ?>
                            <?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1><?php echo isset($ownername); ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
                            </font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $work_as_kit_box; ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a
                                    target="_blank"
                                    href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View'
                                    id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $bpallet_qty; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $b2b_fob; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $b2b_cost; ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a
                                href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>'
                                onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
                            <?php } ?></td>

                    <tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="14"
                            style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                            <div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
                        </td>
                    </tr>

                    <?php
											$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $inv['actual_inventory'] . "</font></td>";

											if ($sales_order_qty_new > 0) {
												$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
												if ($inv['availability'] == '3') $inv_row .= "<b>";
												$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
											} else {
												$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
												if ($inv['availability'] == '3') $inv_row .= "<b>";
												$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
											}

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' >";
											if ($inv['availability'] == '3') $inv_row .= '<b>';
											if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
											if ($inv['availability'] == '2') $inv_row .= 'Available Now';
											if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
											if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
											if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
											if ($inv['availability'] == '-1') $inv_row .= 'Presell';
											if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
											if ($inv['availability'] == '-3') $inv_row .= 'Potential';
											if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
											$inv_row .= "</td>  ";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											$inv_row .= isset($ownername) . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $vendor_name . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
											$inv_row .= "<td bgColor='$bg' class='style12left' >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											if ($loop['id'] < 0) {
												$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
											} else {
												$inv_row .= $inv['N'] . "</a>";
											}
											$inv_row .= "</td>";
											$inv_row .= "</tr>";

											if ($inv["after_actual_inventory"] > 0) {
												if ($inv["availability"] == "3") {
													if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
														//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
														//$no_of_urgent_load_str .= $inv_row;
													}
												}
											}
											//&& ($boxes_per_trailer >= $inv["actual_inventory"])
											if ($inv["actual_inventory"] > 0) {
												if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
													$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
													$no_of_full_load_str .= $inv_row;
												}

												$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob);
											}

											if ($inv["actual_inventory"] > 0) {
												$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
												$no_of_full_load_str_ucb_inv_str .= $inv_row;
											}

											if ($inv["after_actual_inventory"] > 0) {
												$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
												$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
											}

											//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
											if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
												if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
													if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
														$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
														$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob;

														$tot_load_available_str .= $inv_row;
													}
												}
											}

											if ($inv["actual_inventory"] < 0) {
												$no_of_red_on_page = $no_of_red_on_page + 1;
												$no_of_red_on_page_str .= $inv_row;
											}

											$notes_date = new DateTime($inv["DT"]);
											$curr_date = new DateTime();

											$notes_date_diff = $curr_date->diff($notes_date)->format("%d");
											if ($notes_date_diff > 7) {
												$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
												$no_of_inv_item_note_date_str .= $inv_row;
											}
											?>

                    <?php
										$count_arry = $count_arry + 1;
									}
								}

								if ($top_head_flg_output == "yes") {
										?>
                </table>
            </div>
        </td>
    </tr>

    <tr align="middle">
        <td>&nbsp;</td>
    </tr>
    <?php }


								$top_head_flg = "no";
								$top_head_flg_output = "no";
						?>

    <?php
						$x = 0;


						$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.box_type = 'SupersacknonUCB' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
						//echo $sql . "<br>";
						db_b2b();
						$dt_view_res = db_query($sql);

						while ($inv = array_shift($dt_view_res)) {
							$vendor_name = "";
							//account owner
							if ($inv["vendor_b2b_rescue"] > 0) {

								$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
								$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
								db();
								$query = db_query($q1);
								while ($fetch = array_shift($query)) {
									$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

									$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
									db_b2b();
									$comres = db_query($comqry);
									while ($comrow = array_shift($comres)) {
										$ownername = $comrow["initials"];
									}
								}
							} else {
								$vendor_b2b_rescue = $inv["V"];
								$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
								db_b2b();
								$query = db_query($q1);
								while ($fetch = array_shift($query)) {
									$vendor_name = $fetch["Name"];

									$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
									db_b2b();
									$comres = db_query($comqry);
									while ($comrow = array_shift($comres)) {
										$ownername = $comrow["initials"];
									}
								}
							}
							//
							$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
							db();
							$loop_res = db_query($loopsql);

							$loop = array_shift($loop_res);

							if ($x == 0) {
								$x = 1;
								$bg = "#e4e4e4";
							} else {
								$x = 0;
								$bg = "#f4f4f4";
							}
							$tipStr = "";

							if ($inv["shape_rect"] == "1")

								$tipStr = $tipStr . " Rec ";



							if ($inv["shape_oct"] == "1")

								$tipStr = $tipStr . " Oct ";



							if ($inv["wall_2"] == "1")

								$tipStr = $tipStr . " 2W ";



							if ($inv["wall_3"] == "1")

								$tipStr = $tipStr . " 3W ";



							if ($inv["wall_4"] == "1")

								$tipStr = $tipStr . " 4W ";



							if ($inv["wall_5"] == "1")

								$tipStr = $tipStr . " 5W ";



							if ($inv["top_nolid"] == "1")

								$tipStr = $tipStr . " No Top,";



							if ($inv["top_partial"] == "1")

								$tipStr = $tipStr . " Flange Top, ";



							if ($inv["top_full"] == "1")

								$tipStr = $tipStr . " FFT, ";



							if ($inv["top_hinged"] == "1")

								$tipStr = $tipStr . " Hinge Top, ";



							if ($inv["top_remove"] == "1")

								$tipStr = $tipStr . " Lid Top, ";



							if ($inv["bottom_no"] == "1")

								$tipStr = $tipStr . " No Bottom";



							if ($inv["bottom_partial"] == "1")

								$tipStr = $tipStr . " PB w/o SS";



							if ($inv["bottom_partialsheet"] == "1")

								$tipStr = $tipStr . " PB w/ SS";



							if ($inv["bottom_fullflap"] == "1")

								$tipStr = $tipStr . " FFB";



							if ($inv["bottom_interlocking"] == "1")

								$tipStr = $tipStr . " FB";



							if ($inv["bottom_tray"] == "1")

								$tipStr = $tipStr . " Tray Bottom";



							if ($inv["vents_no"] == "1")

								$tipStr = $tipStr . "";



							if ($inv["vents_yes"] == "1")

								$tipStr = $tipStr . ", Vents";


						?>

    <?php
							$bpallet_qty = 0;
							$boxes_per_trailer = 0;
							$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
							db();
							$dt_view = db_query($qry);
							while ($sku_val = array_shift($dt_view)) {
								$sku = $sku_val['sku'];
								$bpallet_qty = $sku_val['bpallet_qty'];
								$boxes_per_trailer = $sku_val['boxes_per_trailer'];
							}

							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$b2b_fob = "$ " . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2b_cost = "$ " . number_format($b2b_cost, 2);

							$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
							$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
							$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
							$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
							$sales_order_qty_new = 0;
							db();
							$dt_res_so_item1 = db_query($dt_so_item1);
							while ($so_item_row1 = array_shift($dt_res_so_item1)) {
								if ($so_item_row1["sumqty"] > 0) {
									$sales_order_qty_new = $so_item_row1["sumqty"];
								}
							}

							$to_show_data = "no";
							if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
								if (isset($filter_availability) == "truckloadonly") {
									if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
										$to_show_data = "yes";
									}
								}
								if (isset($filter_availability) == "anyavailableboxes") {
									if ($inv["after_actual_inventory"] > 0) {
										$to_show_data = "yes";
									}
								}
							} else {
								$to_show_data = "yes";
							}

							if ($to_show_data == "yes") {

								$b2b_status = "";
								db();
								$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
								while ($so_item_row1 = array_shift($dt_res_so_item1)) {
									$b2b_status = $so_item_row1["box_status"];
								}

								$top_head_flg_output = "yes";
								if ($top_head_flg == "no") {

									$top_head_flg = "yes";
							?>
    <tr align="middle">
        <td colspan="13" class="style24" style="height: 16px"><strong>Non-UCB Supersack Inventory</strong></td>
    </tr>
    <tr>
        <td colspan="13">
            <div id="div_noninv_supersack" name="div_noninv_supersack">
                <table cellSpacing="1" cellPadding="1" border="0" width="1200">
                    <tr vAlign="left">


                        <td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(2,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(2,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(3,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(3,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(4,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(4,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(16,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(16,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Account Owner&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbsupersack(17,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbsupersack(17,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Supplier&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbsupersack(5,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbsupersack(5,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Ship From&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbsupersack(15,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbsupersack(15,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(6,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(6,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(7,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(7,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(9,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(9,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(10,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(10,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(11,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(11,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(12,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(12,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(13,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(13,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(14,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbsupersack(14,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>
                    </tr>

                    <tr vAlign="left">
                        <td colspan=15>
                            <?php } ?>

                    <tr vAlign="center">

                        <?php if ($sales_order_qty_new > 0) { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font color='blue' size=1>
                                <div
                                    onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
                                    <u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
                                </div>
                            </font>
                        </td>
                        <?php } else { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
                            </font>
                        </td>
                        <?php } ?>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?></a>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?>
                            <?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
                            <?php if ($inv["availability"] == "2") echo "Available Now"; ?>
                            <?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
                            <?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
                            <?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
                            <?php if ($inv["availability"] == "-1") echo "Presell"; ?>
                            <?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
                            <?php if ($inv["availability"] == "-3") echo "Potential"; ?>
                            <?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1><?php echo isset($ownername); ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
                            </font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a
                                    target="_blank"
                                    href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View'
                                    id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $bpallet_qty; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $b2b_fob; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $b2b_cost; ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a
                                href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>'
                                onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
                            <?php } ?></td>

                    <tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="14"
                            style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                            <div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
                        </td>
                    </tr>

                    <?php
													$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= $inv['actual_inventory'] . "</font></td>";

													if ($sales_order_qty_new > 0) {
														$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
														if ($inv['availability'] == '3') $inv_row .= "<b>";
														$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
													} else {
														$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
														if ($inv['availability'] == '3') $inv_row .= "<b>";
														$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
													}

													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12' >";
													if ($inv['availability'] == '3') $inv_row .= '<b>';
													if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
													if ($inv['availability'] == '2') $inv_row .= 'Available Now';
													if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
													if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
													if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
													if ($inv['availability'] == '-1') $inv_row .= 'Presell';
													if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
													if ($inv['availability'] == '-3') $inv_row .= 'Potential';
													if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
													$inv_row .= "</td>  ";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
													$inv_row .= isset($ownername) . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= $vendor_name . "</a></font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob . "</font></td>";
													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' >";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
													$inv_row .= "<td bgColor='$bg' class='style12left' >";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													if ($loop['id'] < 0) {
														$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
													} else {
														$inv_row .= $inv['N'] . "</a>";
													}
													$inv_row .= "</td>";
													$inv_row .= "</tr>";

													if ($inv["after_actual_inventory"] > 0) {
														if ($inv["availability"] == "3") {
															if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																//$no_of_urgent_load_str .= $inv_row;
															}
														}
													}
													//&& ($boxes_per_trailer >= $inv["actual_inventory"])
													if ($inv["actual_inventory"] > 0) {
														if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
															$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
															$no_of_full_load_str .= $inv_row;
														}

														$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob);
													}

													if ($inv["actual_inventory"] > 0) {
														$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
														$no_of_full_load_str_ucb_inv_str .= $inv_row;
													}

													if ($inv["after_actual_inventory"] > 0) {
														$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
														$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
													}

													//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
													if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
														if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
															if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob;

																$tot_load_available_str .= $inv_row;
															}
														}
													}

													if ($inv["actual_inventory"] < 0) {
														$no_of_red_on_page = $no_of_red_on_page + 1;
														$no_of_red_on_page_str .= $inv_row;
													}

													$notes_date = new DateTime($inv["DT"]);
													$curr_date = new DateTime();

													$notes_date_diff = $curr_date->diff($notes_date)->format("%d");
													if ($notes_date_diff > 7) {
														$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
														$no_of_inv_item_note_date_str .= $inv_row;
													}
													?>
                    <?php
												$count_arry = $count_arry + 1;
											}
										}

										if ($top_head_flg_output == "yes") {
												?>
                </table>
            </div>
        </td>
    </tr>

    <tr align="middle">
        <td>&nbsp;</td>
    </tr>
    <?php }

										$top_head_flg = "no";
										$top_head_flg_output = "no";
								?>


    <?php
								$x = 0;


								$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory where inventory.box_type = 'DrumBarrelnonUCB' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
								//echo $sql . "<br>";
								db_b2b();
								$dt_view_res = db_query($sql);

								while ($inv = array_shift($dt_view_res)) {
									$vendor_name = "";
									//account owner
									if ($inv["vendor_b2b_rescue"] > 0) {

										$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
										db();
										$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									} else {
										$vendor_b2b_rescue = $inv["V"];
										$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
										db_b2b();
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$vendor_name = $fetch["Name"];

											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									}
									//

									$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
									db();
									$loop_res = db_query($loopsql);

									$loop = array_shift($loop_res);

									if ($x == 0) {
										$x = 1;
										$bg = "#e4e4e4";
									} else {
										$x = 0;
										$bg = "#f4f4f4";
									}
									$tipStr = "";

									if ($inv["shape_rect"] == "1")

										$tipStr = $tipStr . " Rec ";



									if ($inv["shape_oct"] == "1")

										$tipStr = $tipStr . " Oct ";



									if ($inv["wall_2"] == "1")

										$tipStr = $tipStr . " 2W ";



									if ($inv["wall_3"] == "1")

										$tipStr = $tipStr . " 3W ";



									if ($inv["wall_4"] == "1")

										$tipStr = $tipStr . " 4W ";



									if ($inv["wall_5"] == "1")

										$tipStr = $tipStr . " 5W ";



									if ($inv["top_nolid"] == "1")

										$tipStr = $tipStr . " No Top,";



									if ($inv["top_partial"] == "1")

										$tipStr = $tipStr . " Flange Top, ";



									if ($inv["top_full"] == "1")

										$tipStr = $tipStr . " FFT, ";



									if ($inv["top_hinged"] == "1")

										$tipStr = $tipStr . " Hinge Top, ";



									if ($inv["top_remove"] == "1")

										$tipStr = $tipStr . " Lid Top, ";



									if ($inv["bottom_no"] == "1")

										$tipStr = $tipStr . " No Bottom";



									if ($inv["bottom_partial"] == "1")

										$tipStr = $tipStr . " PB w/o SS";



									if ($inv["bottom_partialsheet"] == "1")

										$tipStr = $tipStr . " PB w/ SS";



									if ($inv["bottom_fullflap"] == "1")

										$tipStr = $tipStr . " FFB";



									if ($inv["bottom_interlocking"] == "1")

										$tipStr = $tipStr . " FB";



									if ($inv["bottom_tray"] == "1")

										$tipStr = $tipStr . " Tray Bottom";



									if ($inv["vents_no"] == "1")

										$tipStr = $tipStr . "";



									if ($inv["vents_yes"] == "1")

										$tipStr = $tipStr . ", Vents";


								?>

    <?php
									$bpallet_qty = 0;
									$boxes_per_trailer = 0;
									$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
									db();
									$dt_view = db_query($qry);
									while ($sku_val = array_shift($dt_view)) {
										$sku = $sku_val['sku'];
										$bpallet_qty = $sku_val['bpallet_qty'];
										$boxes_per_trailer = $sku_val['boxes_per_trailer'];
									}

									$b2b_ulineDollar = round($inv["ulineDollar"]);
									$b2b_ulineCents = $inv["ulineCents"];
									$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
									$b2b_fob = "$ " . number_format($b2b_fob, 2);

									$b2b_costDollar = round($inv["costDollar"]);
									$b2b_costCents = $inv["costCents"];
									$b2b_cost = $b2b_costDollar + $b2b_costCents;
									$b2b_cost = "$ " . number_format($b2b_cost, 2);

									$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
									$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
									$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
									$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
									$sales_order_qty_new = 0;
									db();
									$dt_res_so_item1 = db_query($dt_so_item1);
									while ($so_item_row1 = array_shift($dt_res_so_item1)) {
										if ($so_item_row1["sumqty"] > 0) {
											$sales_order_qty_new = $so_item_row1["sumqty"];
										}
									}

									$to_show_data = "no";
									if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
										if (isset($filter_availability) == "truckloadonly") {
											if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
												$to_show_data = "yes";
											}
										}
										if ($filter_availability == "anyavailableboxes") {
											if ($inv["after_actual_inventory"] > 0) {
												$to_show_data = "yes";
											}
										}
									} else {
										$to_show_data = "yes";
									}

									if ($to_show_data == "yes") {

										$b2b_status = "";
										db();
										$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
										while ($so_item_row1 = array_shift($dt_res_so_item1)) {
											$b2b_status = $so_item_row1["box_status"];
										}

										$top_head_flg_output = "yes";
										if ($top_head_flg == "no") {

											$top_head_flg = "yes";
									?>

    <tr align="middle">
        <td colspan="13" class="style24" style="height: 16px"><strong>Non-UCB Drum/Barrel Inventory</strong></td>
    </tr>
    <tr>
        <td colspan="13">
            <div id="div_noninv_drumBarrel" name="div_noninv_drumBarrel">
                <table cellSpacing="1" cellPadding="1" border="0" width="1200">
                    <tr vAlign="left">

                        <td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(2,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(2,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(3,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(3,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(4,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(4,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(16,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(16,2);"><img src="images/sort_desc.jpg"
                                        width="5px;" height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Account Owner&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbdrumBarrel(17,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbdrumBarrel(17,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Supplier&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbdrumBarrel(5,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbdrumBarrel(5,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Ship From&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbdrumBarrel(15,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbdrumBarrel(15,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(6,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(6,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(7,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(7,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(9,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(9,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(10,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(10,2);"><img src="images/sort_desc.jpg"
                                        width="5px;" height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(11,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(11,2);"><img src="images/sort_desc.jpg"
                                        width="5px;" height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(12,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(12,2);"><img src="images/sort_desc.jpg"
                                        width="5px;" height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(13,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(13,2);"><img src="images/sort_desc.jpg"
                                        width="5px;" height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(14,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbdrumBarrel(14,2);"><img src="images/sort_desc.jpg"
                                        width="5px;" height="10px;"></a></b></td>
                    </tr>

                    <tr vAlign="left">
                        <td colspan=15>
                            <?php } ?>

                    <tr vAlign="center">


                        <?php if ($sales_order_qty_new > 0) { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font color='blue' size=1>
                                <div
                                    onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
                                    <u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
                                </div>
                            </font>
                        </td>
                        <?php } else { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
                            </font>
                        </td>
                        <?php } ?>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?>
                            <?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
                            <?php if ($inv["availability"] == "2") echo "Available Now"; ?>
                            <?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
                            <?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
                            <?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
                            <?php if ($inv["availability"] == "-1") echo "Presell"; ?>
                            <?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
                            <?php if ($inv["availability"] == "-3") echo "Potential"; ?>
                            <?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1><?php echo isset($ownername); ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
                            </font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a
                                    target="_blank"
                                    href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View'
                                    id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $bpallet_qty; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $b2b_fob; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $b2b_cost; ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a
                                href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>'
                                onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
                            <?php } ?></td>

                    <tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="14"
                            style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                            <div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
                        </td>
                    </tr>

                    <?php
															$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= $inv['actual_inventory'] . "</font></td>";

															if ($sales_order_qty_new > 0) {
																$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
																if ($inv['availability'] == '3') $inv_row .= "<b>";
																$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
															} else {
																$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
																if ($inv['availability'] == '3') $inv_row .= "<b>";
																$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
															}

															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12' >";
															if ($inv['availability'] == '3') $inv_row .= '<b>';
															if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
															if ($inv['availability'] == '2') $inv_row .= 'Available Now';
															if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
															if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
															if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
															if ($inv['availability'] == '-1') $inv_row .= 'Presell';
															if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
															if ($inv['availability'] == '-3') $inv_row .= 'Potential';
															if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
															$inv_row .= "</td>  ";


															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
															$inv_row .= isset($ownername) . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= $vendor_name . "</a></font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob . "</font></td>";
															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' >";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
															$inv_row .= "<td bgColor='$bg' class='style12left' >";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															if ($loop['id'] < 0) {
																$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
															} else {
																$inv_row .= $inv['N'] . "</a>";
															}
															$inv_row .= "</td>";
															$inv_row .= "</tr>";

															if ($inv["after_actual_inventory"] > 0) {
																if ($inv["availability"] == "3") {
																	if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																		//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																		//$no_of_urgent_load_str .= $inv_row;
																	}
																}
															}
															//&& ($boxes_per_trailer >= $inv["actual_inventory"])
															if ($inv["actual_inventory"] > 0) {
																if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
																	$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
																	$no_of_full_load_str .= $inv_row;
																}

																$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob);
															}

															if ($inv["actual_inventory"] > 0) {
																$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
																$no_of_full_load_str_ucb_inv_str .= $inv_row;
															}

															if ($inv["after_actual_inventory"] > 0) {
																$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
																$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
															}

															//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
															if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
																if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
																	if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																		$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																		$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob;

																		$tot_load_available_str .= $inv_row;
																	}
																}
															}

															if ($inv["actual_inventory"] < 0) {
																$no_of_red_on_page = $no_of_red_on_page + 1;
																$no_of_red_on_page_str .= $inv_row;
															}

															$notes_date = new DateTime($inv["DT"]);
															$curr_date = new DateTime();

															$notes_date_diff = $curr_date->diff($notes_date)->format("%d");
															if ($notes_date_diff > 7) {
																$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
																$no_of_inv_item_note_date_str .= $inv_row;
															}
															?>

                    <?php
														$count_arry = $count_arry + 1;
													}
												}

												if ($top_head_flg_output == "yes") {
														?>
                </table>
            </div>
        </td>
    </tr>

    <tr align="middle">
        <td>&nbsp;</td>
    </tr>
    <?php }

												$top_head_flg = "no";
												$top_head_flg_output = "no";
										?>

    <?php
										$x = 0;


										$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.box_type = 'PalletsnonUCB' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
										//echo $sql . "<br>";
										db_b2b();
										$dt_view_res = db_query($sql);

										while ($inv = array_shift($dt_view_res)) {
											$vendor_name = "";
											//account owner
											if ($inv["vendor_b2b_rescue"] > 0) {

												$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
												$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
												db();
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											} else {
												$vendor_b2b_rescue = $inv["V"];
												$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
												db_b2b();
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = $fetch["Name"];

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											}
											//
											$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
											db();
											$loop_res = db_query($loopsql);

											$loop = array_shift($loop_res);

											if ($x == 0) {
												$x = 1;
												$bg = "#e4e4e4";
											} else {
												$x = 0;
												$bg = "#f4f4f4";
											}
											$tipStr = "";

											if ($inv["shape_rect"] == "1")

												$tipStr = $tipStr . " Rec ";



											if ($inv["shape_oct"] == "1")

												$tipStr = $tipStr . " Oct ";



											if ($inv["wall_2"] == "1")

												$tipStr = $tipStr . " 2W ";



											if ($inv["wall_3"] == "1")

												$tipStr = $tipStr . " 3W ";



											if ($inv["wall_4"] == "1")

												$tipStr = $tipStr . " 4W ";



											if ($inv["wall_5"] == "1")

												$tipStr = $tipStr . " 5W ";



											if ($inv["top_nolid"] == "1")

												$tipStr = $tipStr . " No Top,";



											if ($inv["top_partial"] == "1")

												$tipStr = $tipStr . " Flange Top, ";



											if ($inv["top_full"] == "1")

												$tipStr = $tipStr . " FFT, ";



											if ($inv["top_hinged"] == "1")

												$tipStr = $tipStr . " Hinge Top, ";



											if ($inv["top_remove"] == "1")

												$tipStr = $tipStr . " Lid Top, ";



											if ($inv["bottom_no"] == "1")

												$tipStr = $tipStr . " No Bottom";



											if ($inv["bottom_partial"] == "1")

												$tipStr = $tipStr . " PB w/o SS";



											if ($inv["bottom_partialsheet"] == "1")

												$tipStr = $tipStr . " PB w/ SS";



											if ($inv["bottom_fullflap"] == "1")

												$tipStr = $tipStr . " FFB";



											if ($inv["bottom_interlocking"] == "1")

												$tipStr = $tipStr . " FB";



											if ($inv["bottom_tray"] == "1")

												$tipStr = $tipStr . " Tray Bottom";



											if ($inv["vents_no"] == "1")

												$tipStr = $tipStr . "";



											if ($inv["vents_yes"] == "1")

												$tipStr = $tipStr . ", Vents";


										?>

    <?php
											$bpallet_qty = 0;
											$boxes_per_trailer = 0;
											$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
											db();
											$dt_view = db_query($qry);
											while ($sku_val = array_shift($dt_view)) {
												$sku = $sku_val['sku'];
												$bpallet_qty = $sku_val['bpallet_qty'];
												$boxes_per_trailer = $sku_val['boxes_per_trailer'];
											}

											$b2b_ulineDollar = round($inv["ulineDollar"]);
											$b2b_ulineCents = $inv["ulineCents"];
											$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
											$b2b_fob = "$ " . number_format($b2b_fob, 2);

											$b2b_costDollar = round($inv["costDollar"]);
											$b2b_costCents = $inv["costCents"];
											$b2b_cost = $b2b_costDollar + $b2b_costCents;
											$b2b_cost = "$ " . number_format($b2b_cost, 2);

											$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
											$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
											$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
											$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
											$sales_order_qty_new = 0;
											db();
											$dt_res_so_item1 = db_query($dt_so_item1);
											while ($so_item_row1 = array_shift($dt_res_so_item1)) {
												if ($so_item_row1["sumqty"] > 0) {
													$sales_order_qty_new = $so_item_row1["sumqty"];
												}
											}

											$to_show_data = "no";
											if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
												if (isset($filter_availability) == "truckloadonly") {
													if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
														$to_show_data = "yes";
													}
												}
												if (isset($filter_availability) == "anyavailableboxes") {
													if ($inv["after_actual_inventory"] > 0) {
														$to_show_data = "yes";
													}
												}
											} else {
												$to_show_data = "yes";
											}

											if ($to_show_data == "yes") {

												$b2b_status = "";
												db();
												$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
												while ($so_item_row1 = array_shift($dt_res_so_item1)) {
													$b2b_status = $so_item_row1["box_status"];
												}

												$top_head_flg_output = "yes";
												if ($top_head_flg == "no") {

													$top_head_flg = "yes";
											?>

    <tr align="middle">
        <td colspan="13" class="style24" style="height: 16px"><strong>Non-UCB Pallets Inventory</strong></td>
    </tr>
    <tr>
        <td colspan="13">
            <div id="div_noninv_pallets" name="div_noninv_pallets">
                <table cellSpacing="1" cellPadding="1" border="0" width="1200">
                    <tr vAlign="left">


                        <td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(2,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(2,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Last Month Quantity&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(3,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(3,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(4,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(4,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(16,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(16,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Account Owner&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbpallets(17,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbpallets(17,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Supplier&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbpallets(5,1);"><img src="images/sort_asc.jpg" width="5px;"
                                            height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbpallets(5,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12">
                            <font size=1><b>Ship From&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbpallets(15,1);"><img src="images/sort_asc.jpg"
                                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                        onclick="displaynonucbpallets(15,2);"><img src="images/sort_desc.jpg"
                                            width="5px;" height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(6,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(6,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(7,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(7,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></font>
                        </td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(9,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(9,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(10,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(10,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(11,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(11,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(12,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(12,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(13,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(13,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>

                        <td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(14,1);"><img src="images/sort_asc.jpg" width="5px;"
                                        height="10px;"></a>&nbsp;<a href="javascript:void();"
                                    onclick="displaynonucbpallets(14,2);"><img src="images/sort_desc.jpg" width="5px;"
                                        height="10px;"></a></b></td>
                    </tr>

                    <tr vAlign="left">
                        <td colspan=15>
                            <?php } ?>

                    <tr vAlign="center">

                        <?php if ($sales_order_qty_new > 0) { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font color='blue' size=1>
                                <div
                                    onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
                                    <u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
                                </div>
                            </font>
                        </td>
                        <?php } else { ?>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
                            </font>
                        </td>
                        <?php } ?>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["lastmonthqty"]; ?></a>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?>
                            <?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
                            <?php if ($inv["availability"] == "2") echo "Available Now"; ?>
                            <?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
                            <?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
                            <?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
                            <?php if ($inv["availability"] == "-1") echo "Presell"; ?>
                            <?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
                            <?php if ($inv["availability"] == "-3") echo "Potential"; ?>
                            <?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1><?php echo isset($ownername); ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
                            </font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
                            </font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <font size=1>
                                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a
                                    target="_blank"
                                    href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View'
                                    id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $bpallet_qty; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $b2b_fob; ?></font>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12">
                            <font size=1><?php echo $b2b_cost; ?></font>
                        </td>

                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
                        </td>
                        <td bgColor="<?php echo $bg; ?>" class="style12left">
                            <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a
                                href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>'
                                onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
                            <?php } ?></td>

                    <tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td colspan="14"
                            style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                            <div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
                        </td>
                    </tr>

                    <?php
																	$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= $inv['actual_inventory'] . "</font></td>";

																	if ($sales_order_qty_new > 0) {
																		$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
																		if ($inv['availability'] == '3') $inv_row .= "<b>";
																		$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
																	} else {
																		$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
																		if ($inv['availability'] == '3') $inv_row .= "<b>";
																		$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
																	}

																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12' >";
																	if ($inv['availability'] == '3') $inv_row .= '<b>';
																	if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
																	if ($inv['availability'] == '2') $inv_row .= 'Available Now';
																	if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
																	if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
																	if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
																	if ($inv['availability'] == '-1') $inv_row .= 'Presell';
																	if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
																	if ($inv['availability'] == '-3') $inv_row .= 'Potential';
																	if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
																	$inv_row .= "</td>  ";
																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
																	$inv_row .= "<b>";
																	$inv_row .= isset($ownername) . "</a></font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= $vendor_name . "</a></font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob . "</font></td>";
																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' >";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
																	$inv_row .= "<td bgColor='$bg' class='style12left' >";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	if ($loop['id'] < 0) {
																		$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
																	} else {
																		$inv_row .= $inv['N'] . "</a>";
																	}
																	$inv_row .= "</td>";
																	$inv_row .= "</tr>";

																	if ($inv["after_actual_inventory"] > 0) {
																		if ($inv["availability"] == "3") {
																			if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																				//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																				//$no_of_urgent_load_str .= $inv_row;
																			}
																		}
																	}
																	//&& ($boxes_per_trailer >= $inv["actual_inventory"])
																	if ($inv["actual_inventory"] > 0) {
																		if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
																			$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
																			$no_of_full_load_str .= $inv_row;
																		}

																		$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob);
																	}

																	if ($inv["actual_inventory"] > 0) {
																		$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
																		$no_of_full_load_str_ucb_inv_str .= $inv_row;
																	}

																	if ($inv["after_actual_inventory"] > 0) {
																		$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
																		$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
																	}

																	//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
																	if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
																		if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
																			if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																				$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																				$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob;

																				$tot_load_available_str .= $inv_row;
																			}
																		}
																	}

																	if ($inv["actual_inventory"] < 0) {
																		$no_of_red_on_page = $no_of_red_on_page + 1;
																		$no_of_red_on_page_str .= $inv_row;
																	}

																	$notes_date = new DateTime($inv["DT"]);
																	$curr_date = new DateTime();

																	$notes_date_diff = $curr_date->diff($notes_date)->format("%d");
																	if ($notes_date_diff > 7) {
																		$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
																		$no_of_inv_item_note_date_str .= $inv_row;
																	}
																	?>

                    <?php
																$count_arry = $count_arry + 1;
															}
														}

														if ($top_head_flg_output == "yes") {
																?>
                </table>
            </div>
        </td>
    </tr>
    <?php } ?>

    </table>

    <div id="tempval_focus" name="tempval_focus"></div>
    <div id="tempval1" name="tempval1">
    </div>

    <div id="tempval" name="tempval">

        <?php

													$inv_row = "</table><br><br><table cellSpacing='1' cellPadding='1' border='0' width='1200' >
			<tr vAlign='center'>
			
				<td bgColor='#e4e4e4' class='style12'><b>Actual</b></td>  
				
				<td bgColor='#e4e4e4' class='style12'><b>After PO</b></td>  
				
				<td bgColor='#e4e4e4' class='style12'><b>Last Month Qty</b></td>  
				<td bgColor='#e4e4e4' class='style12'><b>Warehouse</b></td>  
				
				<td bgColor='#e4e4e4' class='style12' width='100px'><font size=1><b>Supplier</b></font></td>	  
				<td bgColor='#e4e4e4' class='style12' width='100px'><font size=1><b>Ship From</b></font></td>	  
				<td bgColor='#e4e4e4' class='style12' width='100px;'><b>Type</b></font></td>	 
				<td bgColor='#e4e4e4' class='style12left' ><b>LxWxH</b></font></td>	  
				<td bgColor='#e4e4e4' class='style12' width='150px;'><b>Description</b></font></td>	 
				<td bgColor='#e4e4e4' class='style12' ><b>SKU</b></td>
				<td bgColor='#e4e4e4' class='style12' width='70px'><b>Per Pallet</b></td>
				<td bgColor='#e4e4e4' class='style12' width='70px;'><b>Per Trailer</b></td>
				
				<td bgColor='#e4e4e4' class='style12' ><b>Min FOB</b></td>
				<td bgColor='#e4e4e4' class='style12' width='70px'><b>Cost</b></td>
			</tr>";
													$no_of_full_load_str .= $inv_row;
													$no_of_urgent_load_str .= $inv_row;
													$tot_load_available_str .= $inv_row;
													$no_of_red_on_page_str .= $inv_row;

													$bg = "#f4f4f4";
													$style12_val = "style12";
													?>
        <table cellSpacing="1" cellPadding="1" border="0" width="1200">
            <tr align="middle">
                <td colspan="13" class="style24" style="height: 16px"><strong>UCB Owned Inventory</strong>
                </td>
            </tr>
            <tr>
                <td colspan="13">
                    <div id="div_ucbinv" name="div_ucbinv">
                        <table cellSpacing="1" cellPadding="1" border="0" width="1200">
                            <tr vAlign="center">

                                <td bgColor="#e4e4e4" class="style12"><b>Actual&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(1,1);"><img src="images/sort_asc.jpg" width="5px;"
                                                height="10px;"></a>&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(1,2);"><img src="images/sort_desc.jpg" width="5px;"
                                                height="10px;"></a></b></td>

                                <td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(2,1);"><img src="images/sort_asc.jpg" width="5px;"
                                                height="10px;"></a>&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(2,2);"><img src="images/sort_desc.jpg" width="5px;"
                                                height="10px;"></a></b></td>

                                <td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(3,1);"><img src="images/sort_asc.jpg" width="5px;"
                                                height="10px;"></a>&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(3,2);"><img src="images/sort_desc.jpg" width="5px;"
                                                height="10px;"></a></b></td>

                                <td bgColor="#e4e4e4" class="style12"><b>Warehouse&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(4,1);"><img src="images/sort_asc.jpg" width="5px;"
                                                height="10px;"></a>&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(4,2);"><img src="images/sort_desc.jpg" width="5px;"
                                                height="10px;"></a></b></td>

                                <td bgColor="#e4e4e4" class="style12" width="100px">
                                    <font size=1><b>Supplier&nbsp;<a href="javascript:void();"
                                                onclick="displayucbinv(5,1);"><img src="images/sort_asc.jpg"
                                                    width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                onclick="displayucbinv(5,2);"><img src="images/sort_desc.jpg"
                                                    width="5px;" height="10px;"></a></b></font>
                                </td>

                                <td bgColor="#e4e4e4" class="style12" width="100px">
                                    <font size=1><b>Ship From&nbsp;<a href="javascript:void();"
                                                onclick="displayucbinv(15,1);"><img src="images/sort_asc.jpg"
                                                    width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                                                onclick="displayucbinv(15,2);"><img src="images/sort_desc.jpg"
                                                    width="5px;" height="10px;"></a></b></font>
                                </td>

                                <td bgColor="#e4e4e4" class="style12" width="100px;"><b>Worked as a kit box?&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(16,1);"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(16,2);"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
                                </td>

                                <td bgColor="#e4e4e4" class="style12" width="100px;"><b>Type&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(6,1);"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(6,2);"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
                                </td>

                                <td bgColor="#e4e4e4" class="style12left"><b>LxWxH&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(7,1);"><img src="images/sort_asc.jpg" width="5px;"
                                                height="10px;"></a>&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(7,2);"><img src="images/sort_desc.jpg" width="5px;"
                                                height="10px;"></a></b></font>
                                </td>

                                <td bgColor="#e4e4e4" class="style12" width="150px;"><b>Description&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(8,1);"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(8,2);"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
                                </td>

                                <td bgColor="#e4e4e4" class="style12" width="70px"><b>Per Pallet&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(10,1);"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(10,2);"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

                                <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Per Trailer&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(11,1);"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(11,2);"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

                                <td bgColor="#e4e4e4" class="style12"><b>Min FOB&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(12,1);"><img src="images/sort_asc.jpg" width="5px;"
                                                height="10px;"></a>&nbsp;<a href="javascript:void();"
                                            onclick="displayucbinv(12,2);"><img src="images/sort_desc.jpg" width="5px;"
                                                height="10px;"></a></b></td>

                                <td bgColor="#e4e4e4" class="style12" width="70px"><b>Cost&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(13,1);"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="javascript:void();" onclick="displayucbinv(13,2);"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

                            </tr>
                            <?php

																		$dt_view_qry = "SELECT * from tmp_inventory_list_set2 $main_new_where_condition_ucbq order by warehouse, type_ofbox, tmp_inventory_list_set2.Description";
																		//echo $dt_view_qry . "<br>";
																		db_b2b();
																		$dt_view_res = db_query($dt_view_qry);

																		// $num_rows = tep_db_num_rows($dt_view_res);
																		// if ($num_rows > 0) 
																		$preordercnt = 1;
																		$newflg = "no";
																		$tmpwarenm = "";
																		$tmp_noofpallet = 0;
																		$ware_house_boxdraw = "";
																		while ($dt_view_row = array_shift($dt_view_res)) {

																			$location_city = "";
																			$location_state = "";
																			$location_zip = "";
																			$loops_id = 0;
																			$vendor_b2b_rescue = 0;
																			$bwall = "";
																			$vendor_id = 0;
																			$expected_loads_per_mo = "";
																			$box_length = "";
																			$box_width = "";
																			$box_height = "";
																			$vendor_name = "";
																			//$dt_view_qry_1 = "SELECT loops_id, location_city, location_state, location_zip, bwall, expected_loads_per_mo, vendor, lengthInch, widthInch, depthInch, vendor_b2b_rescue from inventory where ID = " . $dt_view_row["inv_id"];
																			$dt_view_qry_1 = "SELECT loops_id, location_city, location_state, location_zip, bwall, expected_loads_per_mo, vendor, lengthInch, widthInch, depthInch, vendor_b2b_rescue from inventory where loops_id = " . $dt_view_row["trans_id"];
																			db_b2b();
																			$dt_view_res_1 = db_query($dt_view_qry_1);
																			while ($dt_view_row_1 = array_shift($dt_view_res_1)) {
																				$location_city = $dt_view_row_1["location_city"];
																				$location_state = $dt_view_row_1["location_state"];
																				$location_zip = $dt_view_row_1["location_zip"];
																				$loops_id = $dt_view_row_1["loops_id"];
																				$vendor_b2b_rescue = $dt_view_row_1["vendor_b2b_rescue"];
																				$bwall = $dt_view_row_1["bwall"];
																				$vendor_id = $dt_view_row_1["vendor"];

																				$expected_loads_per_mo = $dt_view_row_1["expected_loads_per_mo"];

																				$box_length = $dt_view_row_1["lengthInch"];
																				$box_width = $dt_view_row_1["widthInch"];
																				$box_height = $dt_view_row_1["depthInch"];
																			}
																			//account owner

																			if ($vendor_b2b_rescue > 0) {

																				$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
																				db();
																				$query = db_query($q1);
																				while ($fetch = array_shift($query)) {
																					$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

																					$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.ID=" . $fetch["b2bid"];
																					db_b2b();
																					$comres = db_query($comqry);
																					while ($comrow = array_shift($comres)) {
																						$ownername = $comrow["initials"];
																					}
																				}
																			} else {
																				$vendor_b2b_rescue = $vendor_id;
																				$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
																				db_b2b();
																				$query = db_query($q1);
																				while ($fetch = array_shift($query)) {
																					$vendor_name = $fetch["Name"];

																					$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
																					db_b2b();
																					$comres = db_query($comqry);
																					while ($comrow = array_shift($comres)) {
																						$ownername = $comrow["initials"];
																					}
																				}
																			}
																			//
																			$dt_view_qry_k = "SELECT work_as_kit_box from loop_boxes where id = " . $dt_view_row["trans_id"];
																			db();
																			$dt_view_res_k = db_query($dt_view_qry_k);
																			while ($dt_view_row_k = array_shift($dt_view_res_k)) {
																				$work_as_kit_box = $dt_view_row_k["work_as_kit_box"];
																			}

																			if ($newflg == "no") {
																				$newflg = "yes";
																		?><tr>
                                <td colspan="13" align="center">Sync on:
                                    <?php echo timeAgo($dt_view_row["updated_on"]); ?></td>
                            </tr><?php
																					}

																					$b2b_fob = $dt_view_row["min_fob"];
																					$b2b_cost = $dt_view_row["cost"];
																					//$vendor_name= $dt_view_row["vendor"];

																					$sales_order_qty = $dt_view_row["sales_order_qty"];

																					if ($dt_view_row["actual"] != 0 or $dt_view_row["actual"] - $sales_order_qty != 0) {
																						$lastmonth_val = $expected_loads_per_mo;

																						$reccnt = 0;
																						if ($sales_order_qty > 0) {
																							$reccnt = $sales_order_qty;
																						}

																						$preorder_txt = "";
																						$preorder_txt2 = "";

																						if ($reccnt > 0) {
																							$preorder_txt = "<u>";
																							$preorder_txt2 = "</u>";
																						}

																						if (($dt_view_row["actual"] >= $dt_view_row["per_trailer"]) && ($dt_view_row["per_trailer"] > 0)) {
																							$bg = "yellow";
																						}

																						$pallet_val = 0;
																						$pallet_val_afterpo = 0;
																						$actual_po = $dt_view_row["actual"] - $sales_order_qty;

																						if ($dt_view_row["per_pallet"] > 0) {
																							$pallet_val = number_format($dt_view_row["actual"] / $dt_view_row["per_pallet"], 1, '.', '');
																							$pallet_val_afterpo = number_format($actual_po / $dt_view_row["per_pallet"], 1, '.', '');
																						}

																						$pallet_space_per = "";

																						if ($pallet_val > 0) {
																							$tmppos_1 = strpos($pallet_val, '.');
																							if ($tmppos_1 != false) {
																								if (intval(substr($pallet_val, strpos($pallet_val, '.') + 1, 1)) > 0) {
																									$pallet_val_temp = $pallet_val;
																									$pallet_val = " (" . $pallet_val_temp . ")";
																								} else {
																									$pallet_val_format = number_format(floatval($pallet_val), 0);
																									$pallet_val = " (" . $pallet_val_format . ")";
																								}
																							} else {
																								$pallet_val_format = number_format(floatval($pallet_val), 0);
																								$pallet_val = " (" . $pallet_val_format . ")";
																							}
																						} else {
																							$pallet_val = "";
																						}

																						if ($pallet_val_afterpo > 0) {
																							$tmppos_1 = strpos($pallet_val_afterpo, '.');
																							if ($tmppos_1 != false) {
																								if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo, '.') + 1, 1)) > 0) {
																									$pallet_val_afterpo_temp = $pallet_val_afterpo;
																									$pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
																								} else {
																									$pallet_val_afterpo_format = number_format(floatval($pallet_val_afterpo), 0);
																									$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
																								}
																							} else {
																								$pallet_val_afterpo_format = number_format(floatval($pallet_val_afterpo), 0);
																								$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
																							}
																						} else {
																							$pallet_val_afterpo = "";
																						}

																						$pallet_space_per = "";

																						$to_show_data = "no";
																						if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
																							if (isset($filter_availability) == "truckloadonly") {
																								if ($actual_po >= $dt_view_row["per_trailer"]) {
																									$to_show_data = "yes";
																								}
																							}
																							if (isset($filter_availability) == "anyavailableboxes") {
																								if ($actual_po > 0) {
																									$to_show_data = "yes";
																								}
																							}
																						} else {
																							$to_show_data = "yes";
																						}

																						if ($to_show_data == "yes") {

																							if ($ware_house_boxdraw != $dt_view_row["warehouse"]) {
																						?><tr>
                                <td colspan="13">
                                    <hr
                                        style="  border: 0; height: 1px;background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));">
                                </td>
                            </tr><?php
																							}
																							$ware_house_boxdraw = $dt_view_row["warehouse"];

																							$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
																							$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
																							$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
																							$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $loops_id . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
																							$sales_order_qty_new = 0;
																							db();
																							$dt_res_so_item1 = db_query($dt_so_item1);
																							while ($so_item_row1 = array_shift($dt_res_so_item1)) {
																								if ($so_item_row1["sumqty"] > 0) {
																									$sales_order_qty_new = $so_item_row1["sumqty"];
																								}
																							}

																								?>
                            <tr vAlign="center">
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <a href='javascript:void();' id='actual_pos<?php echo $dt_view_row["trans_id"]; ?>'
                                        onclick="displayactualpallet(<?php echo $dt_view_row["trans_id"]; ?>);">
                                        <?php
																								if ($dt_view_row["actual"] < 0) { ?>
                                        <font color="red"><?php echo $dt_view_row["actual"] . $pallet_val; ?></font>
                                        <?php	 } else { ?>
                                        <font color="green"><?php echo $dt_view_row["actual"] . $pallet_val; ?></font>
                                        <?php } ?>
                                    </a>
                                </td>
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php
																							if ($actual_po < 0) { ?>
                                    <div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $loops_id; ?>, <?php echo $vendor_b2b_rescue; ?>)"
                                        style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial">
                                        <font color="blue"><?php echo $preorder_txt; ?><?php
																																					echo $actual_po . $pallet_val_afterpo; ?><?php echo $preorder_txt2; ?></font>
                                    </div>
                                    <?php	} else { ?>
                                    <div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $loops_id; ?>, <?php echo $vendor_b2b_rescue; ?>)"
                                        style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial">
                                        <font color="green"><?php echo $preorder_txt; ?><?php
																																					echo $actual_po . $pallet_val_afterpo;
																																					?></font><?php echo $preorder_txt2; ?>
                                    </div> <?php } ?>
                                </td>
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $expected_loads_per_mo; ?></td>
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $dt_view_row["warehouse"]; ?></td>
                                <td bgColor="<?php echo $bg; ?>" class="style12left"><?php echo $vendor_name; ?></td>
                                <td bgColor="<?php echo $bg; ?>" class="style12left">
                                    <?php echo $location_city . ", " . $location_state . " " . $location_zip; ?></td>

                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo isset($work_as_kit_box); ?></td>

                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $dt_view_row["type_ofbox"]; ?></td>

                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $dt_view_row["LWH"]; ?></td>
                                <td bgColor="<?php echo $bg; ?>" class="style12left"><a target="_blank"
                                        href='manage_box_b2bloop.php?id=<?php echo $dt_view_row["trans_id"]; ?>&proc=View'
                                        id='box_div_main<?php echo $dt_view_row["trans_id"]; ?>'><?php echo $dt_view_row["Description"]; ?></a>
                                </td>

                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $dt_view_row["per_pallet"]; ?></td>
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo number_format($dt_view_row["per_trailer"], 0); ?></td>
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    $<?php echo  number_format($dt_view_row["min_fob"], 2); ?></td>
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    $<?php echo  number_format($dt_view_row["cost"], 2); ?></td>

                                <?php
																							$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'>";
																							$inv_row .= "<a href='javascript:void();' id='actual_pos" . $dt_view_row["trans_id"] . "' onclick='displayactualpallet(" . $dt_view_row["trans_id"] . ")'>";
																							//. $pallet_val
																							if ($dt_view_row["actual"] < 0) {
																								$inv_row .= "<font color='red'>" . $dt_view_row["actual"] . "</font>";
																							} else {
																								$inv_row .= "<font color='green'>" . $dt_view_row["actual"] . "</font>";
																							}
																							$inv_row .= "</a></td>";

																							$inv_row .= "<td bgColor='$bg' class='$style12_val' >";
																							if ($actual_po < 0) {
																								$inv_row .= "<div onclick='display_orders_data(" . $count_arry . "," . $loops_id . "," . $vendor_b2b_rescue . ")' style='FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial'><font color='blue'>" . $preorder_txt;
																								$inv_row .= $actual_po . $pallet_val_afterpo . $preorder_txt2 . "</font></div>";
																							} else {
																								$inv_row .= "<div onclick='display_orders_data(" . $count_arry . "," . $loops_id . "," . $vendor_b2b_rescue . ")' style='FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial'><font color='green'>" . $preorder_txt;
																								$inv_row .= $actual_po . $pallet_val_afterpo . "</font>" . $preorder_txt2 . "</div>";
																							}
																							$inv_row .= "</td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12_val'>" . $expected_loads_per_mo . "</td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12_val'>" . $dt_view_row["warehouse"] . "</td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12left'><font size=1>" . $vendor_name . "</font></td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12left'><font size=1>" . $location_city . ", " . $location_state . " " . $location_zip . "</font></td>";

																							$inv_row .= "<td bgColor='$bg' class='$style12_val' >" . $dt_view_row["type_ofbox"] . "</td>";

																							$inv_row .= "<td bgColor='$bg' class='$style12_val'>" . $dt_view_row["LWH"] . "</td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12left' ><a target='_blank' href='manage_box_b2bloop.php?id=" . $dt_view_row["trans_id"] . "&proc=View' id='box_div_main" . $dt_view_row["trans_id"] . "'>" . $dt_view_row["Description"] . "</a></td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12left' ><a target='_blank' href='boxpics/" . $dt_view_row["flyer"] . "' id='box_fly_div_main" . $dt_view_row["trans_id"] . "'>" . $dt_view_row["SKU"] . "</a></td>";

																							$inv_row .= " <td bgColor='$bg' class='$style12_val' >" . $dt_view_row["per_pallet"] . "</td>";
																							$inv_row .= " <td bgColor='$bg' class='$style12_val' >" . number_format($dt_view_row["per_trailer"], 0) . "</td>";
																							$inv_row .= " <td bgColor='$bg' class='$style12_val' >$" .  number_format($dt_view_row["min_fob"], 2) . "</td>";
																							$inv_row .= "</tr>";
																							//$inv_row .= " <td bgColor='$bg' class='$style12_val' >" .  $actual_po . "</td>";

																							if ($dt_view_row["type_ofbox"] != "Recycling") {
																								//&& $dt_view_row["per_trailer"] >= $dt_view_row["actual"]
																								if ($dt_view_row["actual"] > 0) {
																									/*if ($inv["availability"] == "3")
						{
							if (floor($dt_view_row["actual"] / $dt_view_row["per_trailer"]) > 0)
							{
								$no_of_urgent_load = $no_of_urgent_load + floor($dt_view_row["actual"] / $dt_view_row["per_trailer"]);
								$no_of_urgent_load_str .= $inv_row;
							}	
						}*/

																									if (floor($dt_view_row["actual"] / $dt_view_row["per_trailer"]) > 0) {
																										$no_of_full_load = $no_of_full_load + floor($dt_view_row["actual"] / $dt_view_row["per_trailer"]);
																										$no_of_full_load_str .= $inv_row;
																										$tot_value_full_load = $tot_value_full_load + ((floor($dt_view_row["actual"] / $dt_view_row["per_trailer"])) * $dt_view_row["per_trailer"] * $dt_view_row["min_fob"]);
																									}
																								}

																								//&& ($dt_view_row["per_trailer"] >= $actual_po)

																								if (!($dt_view_row["type_ofbox"] == "LoopShipping" || $dt_view_row["type_ofbox"] == "Loop")) {
																									if (($actual_po > 0) && ($dt_view_row["per_trailer"] > 0)) {
																										if (floor($actual_po / $dt_view_row["per_trailer"]) > 0) {
																											$tot_load_available = $tot_load_available + floor($actual_po / $dt_view_row["per_trailer"]);
																											$tot_load_available_val = $tot_load_available_val + (floor($actual_po / $dt_view_row["per_trailer"])) * $dt_view_row["per_trailer"] * $dt_view_row["min_fob"];

																											$tot_load_available_str .= $inv_row;
																										}
																									}
																								}

																								if ($dt_view_row["actual"] > 0) {
																									$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($dt_view_row["actual"] * $dt_view_row["min_fob"]);
																									$no_of_full_load_str_ucb_inv_str .= $inv_row;
																								}

																								if ($actual_po > 0) {
																									$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($actual_po * $dt_view_row["min_fob"]);
																									$no_of_full_load_str_ucb_inv_av_str .= $inv_row . " <td bgColor='$bg' class='$style12_val' >" .  $no_of_full_load_str_ucb_inv_av . "</td>";
																								}
																							}

																							if ($dt_view_row["actual"] < 0) {
																								$no_of_red_on_page = $no_of_red_on_page + 1;
																								$no_of_red_on_page_str .= $inv_row;
																							}

																						?>

                            </tr>
                            <?php
																							if ($x == 0) {
																								$x = 1;
																								$bg = "#e4e4e4";
																							} else {
																								$x = 0;
																								$bg = "#f4f4f4";
																							}

																							if ($reccnt > 0) { ?>
                            <tr id='inventory_preord_org_top_<?php echo $preordercnt; ?>' align="middle"
                                style="display:none;">
                                <td>&nbsp;</td>
                                <td colspan="14"
                                    style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                                    <div id="inventory_preord_org_middle_div_<?php echo $preordercnt; ?>"></div>
                                </td>
                            </tr>

                            <?php	 }
																					?>

                            <tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle"
                                style="display:none;">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="14"
                                    style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                                    <div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
                                </td>
                            </tr>

                            <?php if ($reccnt > 0) { ?>
                            <?php
																								$preordercnt = $preordercnt + 1;
																							}

																							$count_arry = $count_arry + 1;
																						}
																					}
																				}
																		?>
                        </table>
                        <div id="inv_summ_div"></div>
                        <table cellspacing="1" cellpadding="1" border="0">
                            <tr>
                                <td class="style12_new_top" colspan="2">Inventory Summary
                                    <input type="hidden" id="no_of_urgent_load_str" name="no_of_urgent_load_str"
                                        value="<?php echo str_replace('"', "'", $no_of_urgent_load_str); ?>" />
                                    <input type="hidden" id="no_of_full_load_str" name="no_of_full_load_str"
                                        value="<?php echo str_replace('"', "'", $no_of_full_load_str); ?>" />
                                    <input type="hidden" id="no_of_full_load_str_ucb_inv_str"
                                        name="no_of_full_load_str_ucb_inv_str"
                                        value="<?php echo str_replace('"', "'", $no_of_full_load_str_ucb_inv_str); ?>" />
                                    <input type="hidden" id="no_of_full_load_str_ucb_inv_av_str"
                                        name="no_of_full_load_str_ucb_inv_av_str"
                                        value="<?php echo str_replace('"', "'", $no_of_full_load_str_ucb_inv_av_str); ?>" />
                                    <input type="hidden" id="tot_load_available_str" name="tot_load_available_str"
                                        value="<?php echo str_replace('"', "'", $tot_load_available_str); ?>" />
                                    <input type="hidden" id="no_of_red_on_page_str" name="no_of_red_on_page_str"
                                        value="<?php echo str_replace('"', "'", $no_of_red_on_page_str); ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4"># of Urgent Loads</td>
                                <td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_urgent_load_str', 1);"><?php echo $no_of_urgent_load_val; ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4">Total # of Full Loads at UCB</td>
                                <td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_full_load_str', 1);"><?php echo $no_of_full_load; ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4">Total Est. Value of Full Loads at UCB</td>
                                <td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_full_load_str', 1);">$<?php echo number_format($tot_value_full_load, 2); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4">Total Est. Value of ALL UCB Inventory</td>
                                <td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_full_load_str_ucb_inv_str', 1);">$<?php echo number_format($no_of_full_load_str_ucb_inv, 2); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4">Total # of Full Loads Available</td>
                                <td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)"
                                        onclick="return inv_summary('tot_load_available_str', 1);"><?php echo $tot_load_available; ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4">Total Est. Value of Full Loads Available at
                                    UCB</td>
                                <td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)"
                                        onclick="return inv_summary('tot_load_available_str', 1);">$<?php echo number_format($tot_load_available_val, 2); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4">Total Est. Value of ALL UCB Available
                                    Inventory</td>
                                <td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_full_load_str_ucb_inv_av_str', 1);">$<?php echo number_format($no_of_full_load_str_ucb_inv_av, 2); ?></a>
                                </td>
                            </tr>

                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4"># of Red Numbers on Page</td>
                                <td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_red_on_page_str', 1);"><?php echo $no_of_red_on_page; ?></a>
                                </td>
                            </tr>
                            <?php
																		$inv_row = "<tr vAlign='center'><td bgColor='$bg' colspan=2 class='style12'>";
																		$inv_row .= "Transactions list where No Ops Delivery Date and which are not Shipped</td>";
																		$inv_row .= "</tr>";
																		$no_of_trans_no_delv_date_str .= $inv_row;

																		$Gn_corporate_list = "504, 1076, 1073, 532, 1074, 1089, ";
																		$Gn_corporate_list .= "3287,718,787,2019,447,1073,1076,504,1639,532,738,694,3002,1072,1074,1077,1089,1238,616,2114,3126,2901,2902,2898,2899,2900,3010,2915,2904,2917,2905,2906,2907,2908,2909,2910,2912,2913,2914,3003,3129,";
																		$Gn_corporate_list .= "616, 718, 1089, 2596, 694, 1073, ";
																		$Gn_corporate_list .= " 3287,718,787,2019,447,1073,1076,504,1639,532,738,694,3002,1072,1074,1077,1089,1238,616,2114,3126,2901,2902,2898,2899,2900,3010,2915,2904,2917,2905,2906,2907,2908,2909,2910,2912,2913,2914,3003,3129";

																		$dt_so_item1 = "SELECT loop_transaction_buyer.id as I, loop_transaction_buyer.*, loop_warehouse.b2bid, loop_warehouse.company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id ";
																		$dt_so_item1 .= " where loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.recycling_flg = 0 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.warehouse_id not in ($Gn_corporate_list) and (loop_transaction_buyer.ops_delivery_date = '' or loop_transaction_buyer.ops_delivery_date is null)";
																		//echo $dt_so_item1;
																		db();
																		$dt_res_so_item1 = db_query($dt_so_item1);
																		while ($so_item_row1 = array_shift($dt_res_so_item1)) {
																			$no_of_trans_no_delv_date = $no_of_trans_no_delv_date + 1;

																			$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'>";
																			$inv_row .= $so_item_row1["I"] . "</td>";
																			$inv_row .= "<td bgColor='$bg' class='style12'>";
																			$inv_row .= "<a target='_blank' href='viewCompany.php?ID=" . $so_item_row1["b2bid"] . "&show=transactions&warehouse_id=" . $so_item_row1["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $so_item_row1["warehouse_id"] . "&rec_id=" . $so_item_row1["I"] . "&display=buyer_view'>" . getnickname($so_item_row1["company_name"], $so_item_row1["b2bid"]);
																			$inv_row .= "</a></td></tr>";

																			$no_of_trans_no_delv_date_str .= $inv_row;
																		}
																		?>
                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4"># of transactions, No Ops Delivery Date</td>
                                <td class="style12_new2" bgcolor="#f4f4f4">
                                    <input type="hidden" id="no_of_trans_no_delv_date_str"
                                        name="no_of_trans_no_delv_date_str"
                                        value="<?php echo $no_of_trans_no_delv_date_str; ?>" />
                                    <a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_trans_no_delv_date_str', 0);"><?php echo $no_of_trans_no_delv_date; ?></a>
                                </td>
                            </tr>
                            <?php
																		$inv_row = "<tr vAlign='center'><td bgColor='$bg' colspan=2 class='style12'>";
																		$inv_row .= "Transactions list where Planned Delivery Date Passed and which are not Shipped</td>";
																		$inv_row .= "</tr>";
																		$no_of_trans_plann_del_pass_str .= $inv_row;

																		$dt_so_item1 = "SELECT loop_transaction_buyer.id as I, loop_transaction_buyer.*, loop_warehouse.b2bid, loop_warehouse.company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id ";
																		$dt_so_item1 .= " where loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.recycling_flg = 0 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.warehouse_id not in ($Gn_corporate_list) and po_delivery_dt < '" . date("Y-m-d") . "'";
																		db();
																		$dt_res_so_item1 = db_query($dt_so_item1);
																		while ($so_item_row1 = array_shift($dt_res_so_item1)) {
																			$no_of_trans_plann_del_pass = $no_of_trans_plann_del_pass + 1;

																			$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'>";
																			$inv_row .= $so_item_row1["I"] . "</td>";
																			$inv_row .= "<td bgColor='$bg' class='style12'>";
																			$inv_row .= "<a target='_blank' href='viewCompany.php?ID=" . $so_item_row1["b2bid"] . "&show=transactions&warehouse_id=" . $so_item_row1["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $so_item_row1["warehouse_id"] . "&rec_id=" . $so_item_row1["I"] . "&display=buyer_view'>" . getnickname($so_item_row1["company_name"], $so_item_row1["b2bid"]);
																			$inv_row .= "</a></td></tr>";

																			$no_of_trans_plann_del_pass_str .= $inv_row;
																		}
																		?>
                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4"># of transactions, Planned Delivery Date
                                    Passed</td>
                                <td class="style12_new2" bgcolor="#f4f4f4">
                                    <input type="hidden" id="no_of_trans_plann_del_pass_str"
                                        name="no_of_trans_plann_del_pass_str"
                                        value="<?php echo $no_of_trans_plann_del_pass_str; ?>" />
                                    <a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_trans_plann_del_pass_str', 0);"><?php echo $no_of_trans_plann_del_pass; ?></a>
                                </td>
                            </tr>

                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4"># of Inventory Items, Note Date > 1 week</td>
                                <td class="style12_new2" bgcolor="#f4f4f4">
                                    <input type="hidden" id="no_of_inv_item_note_date_str"
                                        name="no_of_inv_item_note_date_str"
                                        value="<?php echo htmlentities($no_of_inv_item_note_date_str); ?>" />
                                    <!-- 
				<input type="hidden" id="no_of_inv_item_note_date_str" name="no_of_inv_item_note_date_str" value="" />-->
                                    <a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_inv_item_note_date_str', 1);"><?php echo $no_of_inv_item_note_date; ?></a>
                                </td>
                            </tr>

                            <?php
																		$inv_row = "<tr vAlign='center'><td bgColor='$bg' colspan=2 class='style12'>";
																		$inv_row .= "List of inventory items NOT completed</td>";
																		$inv_row .= "</tr>";
																		$no_of_inv_not_complete_str .= $inv_row;
																		$no_of_inv_not_complete = 0;

																		$dt_so_item1 = "Select b2b_id, id from loop_boxes ";
																		$dt_so_item1 .= " where inactive = 0 and entry_confirmed_log <> 'Yes'";
																		db();
																		$dt_res_so_item1 = db_query($dt_so_item1);
																		while ($so_item_row1 = array_shift($dt_res_so_item1)) {
																			$no_of_inv_not_complete = $no_of_inv_not_complete + 1;

																			$inv_row = "<tr vAlign='center'>";
																			$inv_row .= "<td bgColor='$bg' class='style12'>";
																			$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $so_item_row1["id"] . "&proc=View&'>" . $so_item_row1["id"];
																			$inv_row .= "</a></td></tr>";

																			$no_of_inv_not_complete_str .= $inv_row;
																		}
																		?>
                            <tr>
                                <td class="style12_new1" bgcolor="#f4f4f4"># of inventory items NOT completed</td>
                                <td class="style12_new2" bgcolor="#f4f4f4">
                                    <input type="hidden" id="no_of_inv_not_complete_str"
                                        name="no_of_inv_not_complete_str"
                                        value="<?php echo htmlentities($no_of_inv_not_complete_str); ?>" />
                                    <a href="javascript:void(0)"
                                        onclick="return inv_summary('no_of_inv_not_complete_str', 2);"><?php echo $no_of_inv_not_complete; ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <?php
											echo "<input type='hidden' id='inventory_preord_totctl' value='$preordercnt' />";
										}
											?>

    <div id="light" class="white_content">
    </div>
    <div id="fade" class="black_overlay"></div>

    <div>
        <?php include("inc/header.php"); ?>
    </div>

    <div class="main_data_css">

        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                B2B Account Pipeline
            </div>
        </div>

        <table border="0" width="1600">

            <tr>
                <td></td>
                <td align=left height=10>
                    <!--<a href="dashboardnew.php">Dashboard</a>&nbsp;&nbsp;
		<a href="index.php">Loops Home page</a>-->

                </td>
            </tr>

            <?php if ($_REQUEST["show"] == "search") {  ?>
            <tr>
                <td colspan=2>
                    <?php
																//searchbox("dashboardnew_account_pipeline_all.php",$eid);
																?>
                    <?php
																showStatusesDashboard_search($viewin, $eid);
																?>
                </td>
            </tr>
            <?php } else { ?>

            <tr style="padding-bottom:1px">
                <td width=200 valign=top style="padding-bottom:1px" border=1px>
                    <font size=2>
                        <?php
																	$x = "SELECT loop_transaction_buyer.id FROM loop_transaction_buyer WHERE  loop_transaction_buyer.order_issue = 1 and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id";
																	db();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=orderissues"><?php echo "Order Issues " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>

                        <!------------------ Begin Open Quote Requests -------->
                        <?php
																	$x = "Select * from quote_request_tracker where (quote_req_status <> 'Quote Sent' and quote_req_deny <> 1)";
																	db();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=openquoterequest"><?php echo "Open Quote Requests " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>
                        <!------------------ End Open Quote Requests -------->

                        <!------------------ Begin Open Quotes -------->
                        <?php
																	$x = "SELECT companyID FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE (qstatus = 1 OR qstatus = 0) AND (quoteType = 'Quote' OR quoteType = 'Quote Select') ";
																	db_b2b();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=openquotes"><?php echo "Open Quotes " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>
                        <!------------------ End Open Quotes -------->

                        <!------------------ Begin Today's Follow-Ups -------->
                        <?php
																	$x = "Select ID from companyInfo Where next_date = CURDATE()";
																	db_b2b();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=followups&period=today"><?php echo "Today's Follow-ups " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>
                        <!------------------ End Today's Follow-Ups -------->

                        <!------------------ Begin Upcoming's Follow-Ups -------->
                        <?php
																	$x = "Select ID from companyInfo Where (companyInfo.next_date > '" . date('Y-m-d') . "' and companyInfo.next_date <= '" . date('Y-m-d', strtotime("+7 days")) . "')";
																	db_b2b();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=followups&period=upcoming"><?php echo "Upcoming Follow-ups " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>
                        <!------------------ End Upcoming's Follow-Ups -------->

                        <!------------------ Begin Old Follow-Ups -------->
                        <?php
																	//$newView = array(47,46,32,50,52,33,38,55,48,61,62,63,6,3,51,56,36,60,0,58,59);
																	//$x = "Select ID from companyInfo Where status in (" . showarrays($newView) .  ")  AND next_date < CURDATE() AND next_date > '1900-01-01'";

																	$x = "Select ID from companyInfo Where status in (" . showarrays($viewin) .  ")  AND next_date < CURDATE() AND next_date > '1900-01-01'";
																	db_b2b();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=followups&period=old"><?php echo "Past Due " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . ")" . $external_leads_Prospects_font_2; ?></a><br><br>
                        <!------------------ End Old Follow-Ups -------->


                        <!------------------ Begin No Follow-Ups -------->
                        <?php
																	$x = "Select ID from companyInfo Where next_date IS NULL";
																	db_b2b();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=followups&period=none"><?php echo "No Follow-ups " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>
                        <!------------------ End No Follow-Ups -------->

                        <!------------------ External Leads/Prospects (X) -------->
                        <?php
																	$x = "Select ID from companyInfo where status in (3,6) and internal_external = 'External'";
																	db_b2b();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=external_leads_Prospects"><?php echo "External Leads/Prospects " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>
                        <!------------------ External Leads/Prospects (X) -------->

                        <!------------------ Internal Leads/Prospects (X) -------->
                        <?php
																	$x = "Select ID from companyInfo Where status in (3,6) and internal_external = 'Internal'";
																	db_b2b();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=internal_leads_Prospects"><?php echo "Internal Leads/Prospects " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>
                        <!------------------ Internal Leads/Prospects (X) -------->

                        <a href="dashboardnew_account_pipeline_all.php?show=preshipped">Closed Deals in
                            Process</a><br><br>

                        <!------------------ Closed Deals Planned Delivery Date Passed (X) -------->
                        <?php
																	$sql = "SELECT loop_transaction_buyer.warehouse_id, loop_transaction_buyer.id, loop_warehouse.warehouse_name, loop_warehouse.b2bid FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id ";
																	$sql .= " WHERE loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 and po_delivery_dt <= DATE_FORMAT(curdate() , '%Y-%m-%d') "; //and bol_shipped_employee =  '$initials'
																	db();
																	$external_leads_Prospects = db_query($sql);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=closed_deal_del_dt_pass"><?php echo "Closed Deals Planned Delivery Date Passed " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>

                        <?php
																	$eid_tmp = $_COOKIE['b2b_id']; // this is the b2b.employees ID number, not the loop_employees ID number
																	if ($eid_tmp == 0) {
																		$eid_tmp = 35;
																	}

																	$x = "Select ";
																	$x .= " status.name as statusname, companyInfo.last_date AS LD,companyInfo.status ,companyInfo.ID AS I,";
																	$x .= " companyInfo.loopid AS LID, companyInfo.contact AS C, companyInfo.dateCreated AS D,";
																	$x .= " companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,";
																	$x .= " companyInfo.city AS CI, companyInfo.state AS ST, companyInfo.zip AS ZI,";
																	$x .= " companyInfo.next_step AS NS, companyInfo.next_date AS ND";
																	$x .= " from companyInfo left join status on companyInfo.status = status.id ";
																	$x .= " Where companyInfo.status not in(24,31,49) and companyInfo.active=1 order by statusname, companyInfo.company ";
																	db_b2b();
																	$external_leads_Prospects = db_query($x);
																	$external_leads_Prospects_cnt = tep_db_num_rows($external_leads_Prospects);
																	$external_leads_Prospects_font = "";
																	$external_leads_Prospects_font_2 = "";
																	if ($external_leads_Prospects_cnt > 0) {
																		$external_leads_Prospects_font = "<font color=red>";
																		$external_leads_Prospects_font_2 = "</font>";
																	}
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=olderthan3months"><?php echo "My > 90 Day, at-Risk Accounts " . $external_leads_Prospects_font . "(" . $external_leads_Prospects_cnt . $external_leads_Prospects_font; ?>)</a><br><br>
                        <!------------------ My > 90 Day, at-Risk Accounts -------->

                        <!------------------ Full Customer List same as "Closed Deals in Process" ------------------------>
                        <a href="dashboardnew_account_pipeline_all.php?show=fullistcustomer">Full Customer
                            List</a><br><br>
                        <!------------------ Full Customer List ------------------------>


                        <!------------------ Statuses------------------------>
                        <?php
																	$dt_view_qry = "SELECT * FROM status WHERE id IN ( " . showarrays($viewin) .  " ) ORDER BY sort_order";
																	//echo $dt_view_qry;
																	db_b2b();
																	$dt_view_res = db_query($dt_view_qry);
																	while ($row = array_shift($dt_view_res)) {

																		if ($row["id"] != 58) { //Everything but special ops 
																			$x = "Select companyInfo.id AS I,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND from companyInfo Where companyInfo.status =" . $row["id"] . " ";
																		} else { //special ops
																			$x = "Select companyInfo.id AS I,  companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND from companyInfo Where companyInfo.special_ops = 1 ";
																		}
																		db_b2b();
																		$data_res_No_Limit = db_query($x);
																		$show = "All";
																		if (tep_db_num_rows($data_res_No_Limit) > 0) {
																	?>
                        <a
                            href="dashboardnew_account_pipeline_all.php?show=status&statusid=<?php echo $row["id"]; ?>"><?php echo $row["name"] . " (" . tep_db_num_rows($data_res_No_Limit); ?>)</a><br><br>
                        <?php
																		}
																	} ?>
                        <!------------------ Statuses------------------------>
                        <font color='red'>*All red numbers are items you need to take care of TODAY.</font><br><br>

                        <a href="report_sales_team_list.php">Sales Call List Report</a><br><br>

                        <a href="report_purchasing_team_list.php">Purchasing Call List Report</a><br><br>

                        <a href="report_b2b_supply_summary.php" target="_blank">B2B Supply Summary</a><br><br>

                        <a href="report_b2b_demand_summary.php" target="_blank">B2B Demand Summary Report</a><br><br>

                        <?php
																	$dt_view_qry = "SELECT ID FROM companyInfo ";
																	$dt_view_qry .= "WHERE loopid > 0 and shipping_receiving_hours = '' and active = 1 and status not in (31) ORDER BY nickname";

																	db_b2b();
																	$dt_view_res = db_query($dt_view_qry);
																	$shipping_receiving_hours = tep_db_num_rows($dt_view_res);
																	$shipping_receiving_hours_font_2 = "";
																	$shipping_receiving_hours_font = "";

																	if ($shipping_receiving_hours > 0) {
																		$shipping_receiving_hours_font = "<font color=red>";
																		$shipping_receiving_hours_font_2 = "</font>";
																	} else {
																		$shipping_receiving_hours_font_2 = "";
																		$shipping_receiving_hours_font = "";
																	}
																	?>
                        <a href="#"
                            onclick="showtable('blankdockhours', '<?php echo isset($sort_order_pre); ?>' , '<?php echo isset($sort); ?>', '<?php echo $eid_tmp; ?>');">Blank
                            Shipping/Receiving Dock Hours
                            <?php echo $shipping_receiving_hours_font . "(" . $shipping_receiving_hours . ")" . $shipping_receiving_hours_font_2; ?>
                        </a><br><br>
                    </font>
                </td>

                <!------------------------- Begin Large Window ------------------------------>

                <td width=1200 valign=top>

                    <div id="divdealinprocess">
                    </div>

                    <?php
																if ($_REQUEST["limit"] == "all") {

																	$show_number = 0;
																}

																if ($_REQUEST["show"] == "searchbox") {
																	//searchbox("dashboardnew_account_pipeline_all.php",$eid); 
																} elseif ($_REQUEST["show"] == "status") {
																	//echo "here" . $_REQUEST["statusid"];

																	$arr = array($_REQUEST["statusid"]);
																	showStatusesDashboard_all($arr, $eid, $show_number, "all");
																} elseif ($_REQUEST["show"] == "status_water") {
																	$arr = array($_REQUEST["statusid"]);
																	showStatusesDashboard_all($arr, $eid, $show_number, "all", 1);
																} elseif ($_REQUEST["show"] == "external_leads_Prospects") {

																	//showStatusesDashboard_all(	explode(",", "3,6"), $eid, $show_number, $_REQUEST["period"], 0, 'external_leads_Prospects');
																	$arr = array(3);
																	showStatusesDashboard_all($arr, $eid, $show_number, $_REQUEST["period"], 0, 'external_leads_Prospects');
																} elseif ($_REQUEST["show"] == "internal_leads_Prospects") {

																	//showStatusesDashboard_all(	explode(",", "3,6"), $eid, $show_number, $_REQUEST["period"], 0, 'internal_leads_Prospects');
																	$arr = array(3);
																	showStatusesDashboard_all($arr, $eid, $show_number, $_REQUEST["period"], 0, 'internal_leads_Prospects');
																} elseif ($_REQUEST["show"] == "closed_deal_del_dt_pass") {

																?>
                    <table border="0">
                        <tr>

                            <td valign="top">
                                <table width="200" cellSpacing="1" cellPadding="1" border="0">
                                    <tr>
                                        <td class="header_td_style" align="center"><strong>Planned Delivery Date
                                                Passed</strong></td>
                                    </tr>
                                    <?php
																					$sql = "SELECT loop_transaction_buyer.warehouse_id, loop_transaction_buyer.id, loop_warehouse.warehouse_name, loop_warehouse.b2bid FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id ";
																					$sql .= " WHERE loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 and po_delivery_dt <= DATE_FORMAT(curdate() , '%Y-%m-%d')"; //and bol_shipped_employee =  '$initials'
																					db();
																					$result = db_query($sql);
																					while ($row = array_shift($result)) {
																						echo "<tr><td bgColor='#E4EAEB'><a target='_blank' href='https://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["id"] . "&display=buyer_ship'><font color=red>" . get_nickname_val($row["warehouse_name"], $row["b2bid"]) . "&nbsp;[" . $row["id"] . "]" . "</font></a>";
																						echo "</td></tr>";
																					}
																					?>
                                </table>
                            </td>
                    </table>
                    <?php
																} elseif ($_REQUEST["show"] == "followups") {

																	//showStatusesDashboard_all(	$viewin, $eid, $show_number, $_REQUEST["period"]);
																	//showStatusesDashboard_all_records(	$viewin, $eid, 'all', $_REQUEST["period"]);
																	include_once('dashboardnew_account_pipeline_all_past_due.php');
																} elseif ($_REQUEST["show"] == "todo") {

																	showtodolist();
																} elseif ($_REQUEST["show"] == "specialops") {
																	$arr = array(58);
																	showStatusesDashboard_all($viewin, $eid, $show_number, $_REQUEST["period"]);
																} elseif ($_REQUEST["show"] == "unassigned") {
																	echo "<a href='report_show_unassign_lead.php' target='_blank'>Click here to View All</a><br><br>";
																	//$arr = array(6,38,42,32,3,51,56,36);
																	$arr = array(38, 32, 3, 51, 56);
																	showStatusesDashboard_all($arr, $eid, 0, all);
																} elseif ($_REQUEST["show"] == "search") {
																	showStatusesDashboard_search($viewin, $eid);
																	//searchbox("dashboardnew_account_pipeline_all.php",$eid);
																?><br><?php
																		//showStatusesDashboard_all(	$viewin, $eid, $show_number, "all");
																		//showLoops();
																	} elseif ($_REQUEST["show"] == "feed") {
																		showfeed(-1);
																	} elseif ($_REQUEST["show"] == "openquotes") {
																		showopenquotes_all($eid);
																	} elseif ($_REQUEST["show"] == "openquoterequest") {
																		if ($_REQUEST["emp_selected"] != "") {
																			showopenquote_request('All', $_REQUEST["emp_selected"]);
																		} else {
																			showopenquote_request('All');
																		}
																	} elseif ($_REQUEST["show"] == "sales_quotas") {
																		sales_quotas($initials);
																	} elseif ($_REQUEST["show"] == "customers") {
																		showCustomerList();
																	} elseif ($_REQUEST["show"] == "inventory") {
																		showinventory_fordashboard_new(0);
																	} elseif ($_REQUEST["show"] == "inventory_cron") {
																		//showinventory_fordashboard_cron_withview(0); 
																		showinventory_fordashboard_invnew(0);
																	} elseif ($_REQUEST["show"] == "inventory_filter") {
																		showinventory_fordashboard_selected(0);
																	} elseif ($_REQUEST["show"] == "inventory_new") {
																		showinventory_fordashboard_invmatch_new(1, 1, 1);
																	} elseif ($_REQUEST["show"] == "oldinventory") {
																		showmap2();
																		echo "<br>";
																		echo "<a href='showmap_all_entry.php'>Show Map with all Boxes</a>";
																		echo "<br><br>";
																		showinventory_fordashboard(0);
																	} elseif ($_REQUEST["show"] == "inventory_old") {
																		showmap2();
																		echo "<br>";
																		echo "<a href='showmap_all_entry.php'>Show Map with all Boxes</a>";
																		echo "<br><br>";
																		showinventory(0);
																	} elseif ($_REQUEST["show"] == "olderthan3months") {
																		showolderthan3months_all();
																	} elseif ($_REQUEST["show"] == "links") {
																		useful_links();


																		?>


                    <table cellSpacing="1" cellPadding="1" border="0" width="500">
                        <tr align="middle">
                            <td class="style24" style="height: 16px">
                                <strong>DASHBOARDS - COMPANIES</strong>
                            </td>
                        </tr>
                        <?php
																		$dashboards = "SELECT * FROM loop_dashboards WHERE company_id > 0 AND Active LIKE 'A' ORDER BY name";
																		db();
																		$dashboard_result = db_query($dashboards);
																		while ($dashboard = array_shift($dashboard_result)) {
																		?>

                        <tr>
                            <td valign="middle" align="center" bgcolor="#E4E4E4">
                                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                                    <a target="_blank"
                                        href="<?php echo $dashboard["webaddress"]; ?>"><?php echo $dashboard["name"]; ?></a>
                                </font>
                            </td>
                        </tr>
                        <?php
																		}
																		?>
                    </table>



                    <table cellSpacing="1" cellPadding="1" border="0" width="500">
                        <tr align="middle">
                            <td class="style24" style="height: 16px">
                                <strong>Warehouse/Office Dashboards</strong>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" bgcolor="#E4E4E4">
                                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                                    <a target="_blank"
                                        href="https://loops.usedcardboardboxes.com/huntvalleywarehouse_159265234358979.php">HV
                                        Warehouse</a>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" bgcolor="#E4E4E4">
                                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                                    <a target="_blank"
                                        href="https://loops.usedcardboardboxes.com/huntvalleyoffice_159265234358979.php">HV
                                        Office</a>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" bgcolor="#E4E4E4">
                                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                                    <a target="_blank"
                                        href="https://loops.usedcardboardboxes.com/hannibalwarehouse_141592653.php">HA
                                        Warehouse</a>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" bgcolor="#E4E4E4">
                                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                                    <a target="_blank"
                                        href="https://loops.usedcardboardboxes.com/hannibaloffice_141592653.php">HA
                                        Office</a>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" bgcolor="#E4E4E4">
                                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                                    <a target="_blank"
                                        href="https://loops.usedcardboardboxes.com/milwaukeeywarehouse_14159265358979.php">ML
                                        Warehouse</a>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" bgcolor="#E4E4E4">
                                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                                    <a target="_blank"
                                        href="https://loops.usedcardboardboxes.com/hktranswarehouse_1223644451.php">HK
                                        Warehouse</a>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" bgcolor="#E4E4E4">
                                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                                    <a target="_blank"
                                        href="https://loops.usedcardboardboxes.com/losangeleswarehouse_23541415243923422653.php">LA
                                        Office</a>
                                </font>
                            </td>
                        </tr>
                    </table>

                    <?php
																	} elseif ($_REQUEST["show"] == "contacts") {
																		showcontacts($initials);
																	} elseif ($_REQUEST["show"] == "freightcalendar") {
																?>
                    <iframe
                        src="https://docs.google.com/spreadsheet/ccc?key=0Akv0bNDB5PrkdElxYm1hNVN6TGhERU5rY3R1cjVSaGc#gid=23"
                        width="1400" height="1000"></iframe>
                    <?php

																	} elseif ($_REQUEST["show"] == "orderissues") {

																		$sort_order_pre = "ASC";
																		if ($_GET['sort_order_pre'] == "ASC") {
																			$sort_order_pre = "DESC";
																		} else {
																			$sort_order_pre = "ASC";
																		}
																?>

                    <table>
                        <tr>
                            <td class="style24" colspan=19 style="height: 16px" align="middle"><strong>ORDER
                                    ISSUES</strong></td>
                        </tr>
                        <tr>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=ID&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>ID</strong></a>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=company_name&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Company</strong></a>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=last_note_text&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Last
                                        Note</strong></a></th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=po_upload_date&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>PO
                                        Upload Date</strong></a></th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=po_delivery_dt&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Planned
                                        Delivery Date</strong></a></th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=source&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Source</strong></a>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=quantity&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Quantity</strong></a>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=ship_date&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Ship
                                        Date</strong></a></th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=last_action&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Last
                                        Action</strong></a></th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=next_action&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Next
                                        Action</strong></a></th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
                                <strong>Order</strong>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
                                <strong>Ship</strong>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
                                <strong>Delivery</strong>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
                                <strong>Pay</strong>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
                                <strong>Vendor</strong>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=invoice_amount&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Invoiced
                                        Amount</strong></a></th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=balance&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Balance</strong></a>
                            </th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a
                                    href='dashboardnew_account_pipeline_all.php?show=orderissues&sort=invoice_age&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Invoice
                                        Age</strong></a></th>
                            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Remove
                                    from List</strong></th>
                        </tr>
                        <?php
																		$dt_view_qry = "SELECT loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery,loop_transaction_buyer.po_delivery_dt,  loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE  loop_transaction_buyer.order_issue = 1 and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																		db();
																		$dt_view_res = db_query($dt_view_qry);
																		while ($dt_view_row = array_shift($dt_view_res)) {

																			$activeflg_str = "";
																			if ($dt_view_row["Active"] == 0) {
																				$activeflg_str = "<font face='arial' size='2' color='red'><b>&nbsp;INACTIVE</b><font>";
																			}

																			//This is the payment Info for the Customer paying UCB
																			$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
																			db();
																			$payment_qry = db_query($payments_sql);
																			$payment = array_shift($payment_qry);

																			//This is the payment info for UCB paying the related vendors
																			$vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
																			db();
																			$vendor_qry = db_query($vendor_sql);
																			$vendor = array_shift($vendor_qry);

																			//Info about Shipment
																			$bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $dt_view_row["I"] . "' ORDER BY id DESC";
																			db();
																			$bol_file_res = db_query($bol_file_qry);
																			$bol_file_row = array_shift($bol_file_res);

																			$fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $dt_view_row["I"];
																			db();
																			$fbookresult = db_query($fbooksql);
																			$freightbooking = array_shift($fbookresult);

																			//Last tansaction Note
																			$sql_ln = "SELECT * FROM loop_transaction_notes WHERE loop_transaction_notes.company_id = " . $dt_view_row["D"] . " and loop_transaction_notes.rec_id = " . $dt_view_row["I"] . " ORDER BY id DESC LIMIT 0,1";
																			db();
																			$result_ln = db_query($sql_ln);
																			$last_note = array_shift($result_ln);


																			$last_note_text = $last_note["message"];
																			$last_note_date = $last_note["date"];

																			/*if ($dt_view_row["po_delivery_dt"] <> "") {
				$Planned_delivery_date = $dt_view_row["po_delivery"]." ". date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
			}else {
				$Planned_delivery_date = $dt_view_row["po_delivery"];
			}*/

																			if ($dt_view_row["po_delivery_dt"] == "") {
																				$Planned_delivery_date = $dt_view_row["po_delivery"];
																			} else {
																				$Planned_delivery_date = date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
																			}

																			$vendors_paid = 0; //Are the vendors paid
																			$vendors_entered = 0; //Has a vendor transaction been entered?
																			$invoice_paid = 0; //Have they paid their invoice?
																			$invoice_entered = 0; //Has the inovice been entered
																			$signed_customer_bol = 0; 	//Customer Signed BOL Uploaded
																			$courtesy_followup = 0; 	//Courtesy Follow Up Made
																			$delivered = 0; 	//Delivered
																			$signed_driver_bol = 0; 	//BOL Signed By Driver
																			$shipped = 0; 	//Shipped
																			$bol_received = 0; 	//BOL Received @ WH
																			$bol_sent = 0; 	//BOL Sent to WH"
																			$bol_created = 0; 	//BOL Created
																			$freight_booked = 0; //freight booked
																			$sales_order = 0;   // Sales Order entered
																			$po_uploaded = 0;  //po uploaded 

																			//Are all the vendors paid?
																			if ($vendor["B"] == 2 && $vendor["C"] == 2) {
																				$vendors_paid = 1;
																			}

																			//Have we entered a vendor transaction?
																			if ($vendor["A"] > 0) {
																				$vendors_entered = 1;
																			}

																			//Have they paid their invoice?
																			if (number_format((float)$dt_view_row["F"], 2) == number_format((float)$payment["A"], 2) && $dt_view_row["F"] != "") {
																				$invoice_paid = 1;
																			}
																			if ($dt_view_row["no_invoice"] == 1) {
																				$invoice_paid = 1;
																			}

																			//Has an invoice amount been entered?
																			if ($dt_view_row["F"] > 0) {
																				$invoice_entered = 1;
																			}

																			if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") {
																				$signed_customer_bol = 1;
																			}	//Customer Signed BOL Uploaded
																			if ($bol_file_row["bol_shipment_followup"] > 0) {
																				$courtesy_followup = 1;
																			}	//Courtesy Follow Up Made
																			if ($bol_file_row["bol_shipment_received"] > 0) {
																				$delivered = 1;
																			}	//Delivered
																			if ($bol_file_row["bol_signed_file_name"] != "") {
																				$signed_driver_bol = 1;
																			}	//BOL Signed By Driver
																			if ($bol_file_row["bol_shipped"] > 0) {
																				$shipped = 1;
																			}	//Shipped
																			if ($bol_file_row["bol_received"] > 0) {
																				$bol_received = 1;
																			}	//BOL Received @ WH
																			if ($bol_file_row["bol_sent"] > 0) {
																				$bol_sent = 1;
																			}	//BOL Sent to WH"
																			if ($bol_file_row["id"] > 0) {
																				$bol_created = 1;
																			}	//BOL Created

																			if ($freightbooking["id"] > 0) {
																				$freight_booked = 1;
																			} //freight booked

																			if (($dt_view_row["G"] == 1)) {
																				$sales_order = 1;
																			} //sales order created
																			if ($dt_view_row["H"] != "") {
																				$po_uploaded = 1;
																			} //po uploaded 


																			$boxsource = "";
																			$box_qry = "SELECT loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.typeid = 1 AND loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
																			db();
																			$box_res = db_query($box_qry);
																			while ($box_row = array_shift($box_res)) {
																				$boxsource = $box_row["C"];
																			}
																			//echo $box_qry;
																			$dt_view_row2 = "";
																			$last_action_str = "";
																			$next_action_str = "";
																			$balance = 0;
																			$invoice_age = 0;
																			$end_time = 0;
																			$start_t = 0;

																			if ($shipped == 0) {

																				$dt_view_qry2 = "SELECT SUM(loop_bol_tracking.qty) AS A, loop_bol_tracking.bol_STL1 AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.warehouse_id AS D, loop_bol_tracking.bol_pickupdate AS E, loop_bol_tracking.quantity1 AS Q1, loop_bol_tracking.quantity2 AS Q2, loop_bol_tracking.quantity3 AS Q3 FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $dt_view_row["I"];
																				db();
																				$dt_view_res2 = db_query($dt_view_qry2);
																				$dt_view_row2 = array_shift($dt_view_res2);

																				if ($invoice_paid == 1) {
																					if ($vendors_paid == 1) {
																						$last_action_str = "Vendors Paid";
																					} elseif ($vendors_entered == 1) {
																						$last_action_str = "Vendors Invoiced";
																					} else {
																						$last_action_str = "Customer Paid";
																					}
																				} elseif ($invoice_entered == 1) {
																					$last_action_str = "Customer Invoiced";
																				} elseif ($signed_customer_bol == 1) {
																					$last_action_str = "Customer Signed BOL";
																				} elseif ($courtesy_followup == 1) {
																					$last_action_str = "Courtesy Followup Made";
																				} elseif ($delivered == 1) {
																					$last_action_str = "Delivered";
																				} elseif ($signed_driver_bol == 1) {
																					$last_action_str = "Shipped - Driver Signed";
																				} elseif ($shipped == 1) {
																					$last_action_str = "Shipped";
																				} elseif ($bol_received == 1) {
																					$last_action_str = "BOL @ Warehouse";
																				} elseif ($bol_sent == 1) {
																					$last_action_str = "BOL Sent to Warehouse";
																				} elseif ($bol_created == 1) {
																					$last_action_str = "BOL Created";
																				} elseif ($freight_booked == 1) {
																					$last_action_str = "Freight Booked";
																				} elseif ($sales_order == 1) {
																					$last_action_str = "Sales Order Entered";
																				} elseif ($po_uploaded == 1) {
																					$last_action_str = "PO Uploaded";
																				}


																				if ($invoice_paid == 1) {
																					if ($vendors_paid == 1) {
																						$next_action_str = "Complete";
																					} elseif ($vendors_entered == 1) {
																						$next_action_str = "Pay Vendor";
																					} else {
																						$next_action_str = "Enter Vendor Invoices";
																					}
																				} elseif ($invoice_entered == 1) {
																					$next_action_str = "Customer to Pay";
																				} elseif ($signed_customer_bol == 1) {
																					$next_action_str = "Invoice Customer";
																				} elseif ($courtesy_followup == 1) {
																					$next_action_str = "Invoice Customer";
																				} elseif ($delivered == 1) {
																					$next_action_str = "Send Courtesy Folllow-up";
																				} elseif ($signed_driver_bol == 1) {
																					$next_action_str = "Confirm Delivery";
																				} elseif ($shipped == 1) {
																					$next_action_str = "Upload Signed BOL";
																				} elseif ($bol_received == 1) {
																					$next_action_str = "Ready to Ship";
																				} elseif ($bol_sent == 1) {
																					$next_action_str = "Confirm BOL Receipt @ Warehouse";
																				} elseif ($bol_created == 1) {
																					$next_action_str = "Send BOL to Warehouse";
																				} elseif ($freight_booked == 1) {
																					$next_action_str = "Create BOL";
																				} elseif ($sales_order == 1) {
																					$next_action_str = "Book Freight";
																				} elseif ($po_uploaded == 1) {
																					$next_action_str = "Enter Sales Order";
																				}

																				$dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
																				db();
																				$dt_view_res3 = db_query($dt_view_qry3);
																				$dt_view_row3 = array_shift($dt_view_res3);
																				$balance = number_format(($dt_view_row["F"] - $dt_view_row3["PAID"]), 2);

																				$start_t = strtotime($dt_view_row["J"]);
																				$end_time =  strtotime(now);
																				$invoice_age = number_format(($end_time - $start_t) / (3600 * 24), 0);
																			}	//if paid
																			$sort_warehouse_id = $dt_view_row["D"];
																			$sort_id = $dt_view_row["I"];
																			//$sort_company_name = $dt_view_row["B"];
																			$sort_company_name = getnickname($dt_view_row["B"], $dt_view_row["b2bid"]);
																			$sort_last_note = strtolower($last_note["message"]);
																			$sort_last_note_dt = $last_note["date"];
																			$sort_po_delivery_dt = $Planned_delivery_date;
																			$sort_source = strtolower($boxsource);
																			//$sort_quantity =  ($dt_view_row2["A"]+$dt_view_row2["Q1"]+$dt_view_row2["Q2"]+$dt_view_row2["Q3"]);
																			$sort_quantity = (isset($dt_view_row2["A"], $dt_view_row2["Q1"], $dt_view_row2["Q2"], $dt_view_row2["Q3"]) && is_array($dt_view_row2)) ? ($dt_view_row2["A"] + $dt_view_row2["Q1"] + $dt_view_row2["Q2"] + $dt_view_row2["Q3"]) : 0;

																			//$sort_ship_date = $dt_view_row2["E"];
																			$sort_ship_date = is_array($dt_view_row2) ? $dt_view_row2["E"] : null;

																			$sort_last_action = $last_action_str;
																			$sort_next_action = $next_action_str;
																			//$sort_invoice_amount = number_format($dt_view_row["F"], 2);
																			$sort_invoice_amount = number_format((float)$dt_view_row["F"], 2);
																			$sort_balance = $balance;
																			$sort_invoice_age = $invoice_age;
																			$sort_flag = $activeflg_str;

																			$MGArray[] = array(
																				'warehouse_id' => $sort_warehouse_id, 'ID' => $sort_id, 'company_name' => $sort_company_name, 'last_note_text' => $sort_last_note, 'po_upload_date' => $sort_last_note_dt, 'po_delivery_dt' => $sort_po_delivery_dt, 'source' => $sort_source, 'quantity' => $sort_quantity, 'ship_date' => $sort_ship_date, 'last_action' => $sort_last_action, 'next_action' => $sort_next_action, 'invoice_amount' => $sort_invoice_amount, 'balance' => $sort_balance, 'invoice_age' => $sort_invoice_age, 'active' => $sort_flag,
																				'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'shipped' => $shipped, 'bol_created' => $bol_created, 'courtesy_followup' => $courtesy_followup,
																				'delivered' => $delivered, 'invoice_paid' => $invoice_paid, 'invoice_entered' => $invoice_entered, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered
																			);
																		}

																		if ($_GET['sort'] == "ID" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_I = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_I[] = $MGArraytmp['ID'];
																			}
																			array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
																		}
																		if ($_GET['sort'] == "ID" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_I = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_I[] = $MGArraytmp['ID'];
																			}
																			array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
																		}
																		//////
																		if ($_GET['sort'] == "company_name" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_B = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_B[] = $MGArraytmp['company_name'];
																			}
																			array_multisort($MGArraysort_B, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "company_name" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_B = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_B[] = $MGArraytmp['company_name'];
																			}
																			array_multisort($MGArraysort_B, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		//////////
																		if ($_GET['sort'] == "last_note_text" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_C = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_C[] = $MGArraytmp['last_note_text'];
																			}
																			array_multisort($MGArraysort_C, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "last_note_text" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_C = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_C[] = $MGArraytmp['last_note_text'];
																			}

																			array_multisort($MGArraysort_C, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////
																		if ($_GET['sort'] == "po_upload_date" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_D = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_D[] = $MGArraytmp['po_upload_date'];
																			}
																			array_multisort($MGArraysort_D, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "po_upload_date" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_D = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_D[] = $MGArraytmp['po_upload_date'];
																			}
																			array_multisort($MGArraysort_D, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////
																		if ($_GET['sort'] == "po_delivery_dt" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_E = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_E[] = $MGArraytmp['po_delivery_dt'];
																			}
																			array_multisort($MGArraysort_E, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "po_delivery_dt" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_E = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_E[] = $MGArraytmp['po_delivery_dt'];
																			}
																			array_multisort($MGArraysort_E, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		/////////
																		if ($_GET['sort'] == "source" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_F = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_F[] = $MGArraytmp['source'];
																			}
																			array_multisort($MGArraysort_F, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "source" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_F = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_F[] = $MGArraytmp['source'];
																			}
																			array_multisort($MGArraysort_F, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		////////		
																		if ($_GET['sort'] == "quantity" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_G = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_G[] = $MGArraytmp['quantity'];
																			}
																			array_multisort($MGArraysort_G, SORT_ASC, SORT_NUMERIC, $MGArray);
																		}
																		if ($_GET['sort'] == "quantity" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_G = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_G[] = $MGArraytmp['quantity'];
																			}
																			array_multisort($MGArraysort_G, SORT_DESC, SORT_NUMERIC, $MGArray);
																		}
																		//////////
																		if ($_GET['sort'] == "ship_date" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_H = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_H[] = $MGArraytmp['ship_date'];
																			}
																			array_multisort($MGArraysort_H, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "ship_date" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_H = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_H[] = $MGArraytmp['ship_date'];
																			}
																			array_multisort($MGArraysort_H, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		//////////			
																		if ($_GET['sort'] == "last_action" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_J = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_J[] = $MGArraytmp['last_action'];
																			}
																			array_multisort($MGArraysort_J, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "last_action" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_J = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_J[] = $MGArraytmp['last_action'];
																			}
																			array_multisort($MGArraysort_J, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////////
																		if ($_GET['sort'] == "next_action" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_K = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_K[] = $MGArraytmp['next_action'];
																			}
																			array_multisort($MGArraysort_K, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "next_action" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_K = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_K[] = $MGArraytmp['next_action'];
																			}
																			array_multisort($MGArraysort_K, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////
																		if ($_GET['sort'] == "invoice_amount" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_L = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_L[] = $MGArraytmp['invoice_amount'];
																			}
																			array_multisort($MGArraysort_L, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "invoice_amount" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_L = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_L[] = $MGArraytmp['invoice_amount'];
																			}
																			array_multisort($MGArraysort_L, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		////////
																		if ($_GET['sort'] == "balance" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_M = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_M[] = $MGArraytmp['balance'];
																			}
																			array_multisort($MGArraysort_M, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "balance" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_M = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_M[] = $MGArraytmp['balance'];
																			}
																			array_multisort($MGArraysort_M, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////
																		if ($_GET['sort'] == "invoice_age" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_N = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_N[] = $MGArraytmp['invoice_age'];
																			}
																			array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "invoice_age" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_N = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_N[] = $MGArraytmp['invoice_age'];
																			}
																			array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);
																		}

																		$MGArraysort_warehouse_id = array();

																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_warehouse_id[] = $MGArraytmp['warehouse_id'];
																		}

																		$MGArraysort_active = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_MGArraysort_active[] = $MGArraytmp['active'];
																		}

																		$MGArraysort_sales_order = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_sales_order[] = $MGArraytmp['sales_order'];
																		}

																		$MGArraysort_po_uploaded = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_po_uploaded[] = $MGArraytmp['po_uploaded'];
																		}
																		$MGArraysort_shipped = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_shipped[] = $MGArraytmp['shipped'];
																		}
																		$MGArraysort_bol_created = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_bol_created[] = $MGArraytmp['bol_created'];
																		}
																		$MGArraysort_courtesy_followup = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_courtesy_followup[] = $MGArraytmp['courtesy_followup'];
																		}
																		$MGArraysort_delivered = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_delivered[] = $MGArraytmp['delivered'];
																		}
																		$MGArraysort_invoice_paid = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_invoice_paid[] = $MGArraytmp['invoice_paid'];
																		}
																		$MGArraysort_invoice_entered = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_invoice_entered[] = $MGArraytmp['invoice_entered'];
																		}
																		$MGArraysort_vendors_paid = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_vendors_paid[] = $MGArraytmp['vendors_paid'];
																		}
																		$MGArraysort_vendors_entered = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_vendors_entered[] = $MGArraytmp['vendors_entered'];
																		}

																		foreach ($MGArray as $MGArraytmp2) { ?>

                        <tr>

                            <td bgColor="#e4e4e4" class="style12">
                                <?php echo $MGArraytmp2['ID']; ?>
                            </td>
                            <td bgColor="#e4e4e4" class="style12">
                                <p align="center">
                                    <span class="infotxt"><u><a
                                                href="https://loops.usedcardboardboxes.com/search_results.php?id=<?php echo $MGArraytmp2['warehouse_id']; ?>&proc=View&searchcrit=&rec_type=Supplier&page=0"><?php echo $MGArraytmp2["company_name"] . $MGArraytmp2["active"]; ?></a></u>
                                        <span style="width:570px;">
                                            <table cellSpacing="1" cellPadding="1" border="0" width="570">
                                                <tr align="middle">
                                                    <td class="style7" colspan="3" style="height: 16px"><strong>SALE
                                                            ORDER DETAILS FOR ORDER ID:
                                                            <?php echo $MGArraytmp2['ID']; ?></strong></td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td bgColor="#e4e4e4" width="70" class="style17">
                                                        <font size=1>
                                                            <strong>QTY</strong>
                                                        </font>
                                                    </td>
                                                    <td bgColor="#e4e4e4" width="100" class="style17">
                                                        <font size=1>
                                                            <strong>Warehouse</strong>
                                                        </font>
                                                    </td>
                                                    <td bgColor="#e4e4e4" width="400" class="style17">
                                                        <font size=1>
                                                            <strong>Box Description</strong>
                                                        </font>
                                                    </td>
                                                </tr>
                                                <?php

																									db();
																									$get_sales_order = db_query("Select *, loop_salesorders.notes AS A, loop_salesorders.pickup_date AS B, loop_salesorders.freight_vendor AS C, loop_salesorders.time AS D, loop_boxes.isbox AS I From loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " . $MGArraytmp2['ID']);

																									while ($boxes = array_shift($get_sales_order)) {
																										$so_notes = $boxes["A"];
																										$so_pickup_date = $boxes["B"];
																										$so_freight_vendor = $boxes["C"];
																										$so_time = $boxes["D"];
																									?>
                                                <tr bgColor="#e4e4e4">
                                                    <td height="13" class="style1" align="right">
                                                        <Font Face='arial' size='1'><?php echo $boxes["qty"]; ?>
                                                    </td>
                                                    <td height="13" style="width: 94px" class="style1" align="right">
                                                        <Font Face='arial' size='1'><?php
																																			$get_wh = "SELECT warehouse_name, b2bid FROM loop_warehouse WHERE id = " . $boxes["location_warehouse_id"];
																																			db();
																																			$get_wh_res = db_query($get_wh);
																																			while ($the_wh = array_shift($get_wh_res)) {
																																				echo getnickname($the_wh["warehouse_name"], $the_wh["b2bid"]);
																																			}
																																			?>
                                                    </td>

                                                    <td align="left" height="13" style="width: 578px" class="style1">
                                                        <?php if ($boxes["I"] == "Y") { ?>
                                                        <font size="1" Face="arial"><?php echo $boxes["blength"]; ?>
                                                            <?php echo $boxes["blength_frac"]; ?> x
                                                            <?php echo $boxes["bwidth"]; ?>
                                                            <?php echo $boxes["bwidth_frac"]; ?> x
                                                            <?php echo $boxes["bdepth"]; ?>
                                                            <?php echo $boxes["bdepth_frac"]; ?>
                                                            <?php echo $boxes["bdescription"]; ?></font>
                                                        <?php } else { ?>
                                                        <font size="1" Face="arial">
                                                            <?php echo $boxes["bdescription"]; ?></font>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php } ?>

                                                <?php
																									$soqry = "Select * From loop_salesorders_manual WHERE trans_rec_id = " . $MGArraytmp2['ID'];
																									db();
																									$get_sales_order2 = db_query($soqry);
																									while ($boxes2 = array_shift($get_sales_order2)) {
																									?>
                                                <tr bgColor="#e4e4e4">
                                                    <td height="13" class="style1" align="right">
                                                        <Font Face='arial' size='1'><?php echo $boxes2["qty"]; ?>
                                                    </td>
                                                    <td height="13" class="style1" align="right">&nbsp;</td>

                                                    <td align="left" height="13" style="width: 578px" class="style1"
                                                        colspan=2>
                                                        <font size="1" Face="arial">
                                                            &nbsp;&nbsp;<?php echo $boxes2["description"]; ?></font>
                                                    </td>
                                                </tr>

                                                <?php	}	?>
                                            </table>
                                        </span>
                                    </span>
                                </p>
                            </td>
                            <td bgColor="#e4e4e4" class="style12">
                                <?php echo ucfirst($MGArraytmp2['last_note_text']); ?>
                            </td>
                            <td bgColor="#e4e4e4" class="style12">
                                <?php echo $MGArraytmp2['po_upload_date']; ?>
                            </td>
                            <td bgColor="#e4e4e4" class="style12">
                                <?php echo $MGArraytmp2['po_delivery_dt']; ?>
                            </td>
                            <td bgColor="#e4e4e4" class="style12">
                                <?php echo ucfirst($MGArraytmp2['source']); ?>
                            </td>
                            <td bgColor="#e4e4e4" class="style12">
                                <?php echo $MGArraytmp2['quantity']; ?>
                            </td>
                            <td bgColor="#e4e4e4" class="style12">
                                <?php echo $MGArraytmp2['ship_date']; ?>
                            </td>
                            <!---- Last Action ------->
                            <td bgColor="#e4e4e4" class="style12">
                                <?php echo $MGArraytmp2["last_action"]; ?>
                            </td>
                            <!---- Next Action ------->
                            <td bgColor="#e4e4e4" class="style12">
                                <?php echo $MGArraytmp2["next_action"]; ?>
                            </td>

                            <?php

																				$open = "<img src=\"images/circle_open.gif\" border=\"0\">";
																				$half = "<img src=\"images/circle__partial.gif\" border=\"0\">";
																				$full = "<img src=\"images/complete.jpg\" border=\"0\">";

																				?>

                            <!------------- ORDERED ---------->
                            <td bgColor="#e4e4e4" class="style12" align="center">
                                <a
                                    href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_view">
                                    <?php
																						if ($MGArraytmp2["sales_order"] == 1) {
																							echo $full;
																						} elseif ($MGArraytmp2["po_uploaded"] == 1) {
																							echo $half;
																						} else {
																							echo $open;
																						} ?>
                                </a>
                            </td>

                            <!------------- SHIPPED ---------->

                            <td bgColor="#e4e4e4" class="style12" align="center">
                                <a
                                    href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_ship">
                                    <?php

																						if ($MGArraytmp2["shipped"] == 1) {
																							echo $full;
																						} elseif ($MGArraytmp2["bol_created"] == 1) {
																							echo $half;
																						} else {
																							echo $open;
																						} ?></a>
                            </td>

                            <!------------- RECEIVED ---------->
                            <td bgColor="#e4e4e4" class="style12" align="center">
                                <a
                                    href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_received">
                                    <?php
																						if ($MGArraytmp2["courtesy_followup"] == 1) {
																							echo $full;
																						} elseif ($MGArraytmp2["delivered"] == 1) {
																							echo $half;
																						} else {
																							echo $open;
																						}

																						?></a>
                            </td>

                            <!------------- PAY ---------->
                            <td bgColor="#e4e4e4" class="style12" align="center">
                                <center>
                                    <a
                                        href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_payment">
                                        <?php

																							if ($MGArraytmp2["invoice_paid"] == 1) {
																								echo $full;
																							} elseif ($MGArraytmp2["invoice_entered"] == 1) {
																								echo $half;
																							} else {
																								echo $open;
																							} ?></a>
                                </center>
                            </td>

                            <!------------- VENDOR ---------->
                            <td bgColor="#e4e4e4" class="style12" align="center">
                                <center>
                                    <a
                                        href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_invoice">
                                        <?php

																							if ($MGArraytmp2["vendors_paid"] == 1) {
																								echo $full;
																							} elseif ($MGArraytmp2["vendors_entered"] == 1) {
																								echo $half;
																							} else {
																								echo $open;
																							} ?></a>
                                </center>
                            </td>

                </td>

                <td bgColor="#e4e4e4" class="style12">
                    <?php echo $MGArraytmp2["invoice_amount"]; ?>
                </td>

                <td bgColor="#e4e4e4" class="style12">
                    <?php echo $MGArraytmp2["balance"]; ?>
                </td>
                <?php

																			if ($MGArraytmp2["invoice_age"] > 30 && $MGArraytmp2["invoice_age"] < 1000) {
															?>
                <td bgColor="#ff0000" class="style12">
                    <?php echo $MGArraytmp2["invoice_age"]; ?>
                </td>
                <?php
																			} elseif (number_format(($end_time - $start_t) / (3600 * 24000), 0) > 10) {
															?>
                <td bgColor="#e4e4e4" class="style12">&nbsp;

                </td>
                <?php
																			} else {
															?>
                <td bgColor="#e4e4e4" class="style12">
                    <?php echo $MGArraytmp2["invoice_age"]; ?>
                </td>
                <?php
																			}
															?>
                <td bgColor="#e4e4e4" class="style12">
                    <input type=button
                        onclick="confirmationIgnore('<?php echo $MGArraytmp2["company_name"]; ?>','<?php echo $MGArraytmp2['ID']; ?>')"
                        value="X">
                </td>
            </tr>
            <?php
																		}	//loop
													?>

        </table>
        <?php
																	} elseif ($_REQUEST["show"] == "preshipped") {
											?>
        <script>
        showdealinprocess(<?php echo "'" . $_COOKIE["userinitials"] . "'" ?>, 'ASC', '');
        </script>
        <?php
																	} elseif ($_REQUEST["show"] == "fullistcustomer") {
																		if (isset($_REQUEST['sort'])) {
												?>
        <script>
        showfullcustomerlist(
            <?php echo "'" . $_COOKIE["b2b_id"] . "', '" . $_GET['sort_order_pre'] .  "', '" . $_GET['sort'] . "'", 'No' . "," . $show_number; ?>
        );
        </script>
        <?php
																		} else {
												?>
        <script>
        showfullcustomerlist('', 'ASC', '', 'No,'.$show_number);
        </script>
        <?php
																		}
																	} elseif ($_REQUEST["show"] == "commissions") { ?>


        <form method=get name="rptcommission" action="dashboardnew_account_pipeline_all.php">
            <p align="left"><b>Commission Report</b></p>
            <table cellspacing="1" border="0">

                <tr>
                    <td>
                        <select name="match_confirmed" id="match_confirmed" onchange="set_paidflg(this)">
                            <option
                                <?php if ((isset($_REQUEST["match_confirmed"]) && ($_REQUEST["match_confirmed"] == "all_c"))) echo " selected "; ?>
                                value="all_c">
                                ALL - NOT PAID TO REP
                            </option>
                            <option value="not_double_chk"
                                <?php if ((isset($_REQUEST["match_confirmed"]) && ($_REQUEST["match_confirmed"] == "not_double_chk"))) echo " selected "; ?>>
                                Needs Double Checked
                            </option>
                            <option value="double_chk_complete"
                                <?php if ((isset($_REQUEST["match_confirmed"]) && ($_REQUEST["match_confirmed"] == "double_chk_complete"))) echo " selected "; ?>>
                                Needs Marked as Paid
                            </option>
                            <option value="commissions_paid"
                                <?php if ((isset($_REQUEST["match_confirmed"]) && ($_REQUEST["match_confirmed"] == "commissions_paid"))) echo " selected "; ?>>
                                Commissions Paid
                            </option>
                        </select>

                    </td>
                    <td>
                        <?php if ((isset($_REQUEST["match_confirmed"]) && ($_REQUEST["match_confirmed"] == "commissions_paid"))) { ?>
                        <div id="showcal">
                            <?php } else { ?>
                            <div id="showcal" style="display:none">
                                <?php } ?>
                                'Mark as paid' date from:
                                <input type="text" name="date_from" id="date_from" size="8"
                                    value="<?php echo isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : ''; ?>">
                                <a href="#"
                                    onclick="cal2xx.select(document.rptcommission.date_from,'dtanchor2xx','yyyy-MM-dd'); return false;"
                                    name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
                                <div ID="listdiv"
                                    STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                </div>

                                To:
                                <input type="text" name="date_to" id="date_to" size="8"
                                    value="<?php echo isset($_REQUEST['date_to']) ? $_REQUEST['date_to'] : ''; ?>">
                                <a href="#"
                                    onclick="cal2xx.select(document.rptcommission.date_to,'dtanchor3xx','yyyy-MM-dd'); return false;"
                                    name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
                                <div ID="listdiv"
                                    STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                </div>

                            </div>
                    </td>
                    <td>

                        <input type=submit value="Run Report" onClick="javascript: return  loadmainpg()">
                        <input type="hidden" id="reprun" name="reprun" value="yes">
                        <input type="text" id="paidunpaid_flg" name="paidunpaid_flg" style="display:none"
                            value="Unpaid">
                        <input type="text" id="show" name="show" style="display:none" value="commissions">
                    </td>
                </tr>
            </table>
        </form>


        <?php
																		function showempdetails(
																			bool $paidunpaid_flg,
																			string $date_from,
																			string $date_to,
																			string $name,
																			string $initials,
																			float $commission,
																			string $empstatus,
																			bool $commission_access,
																			bool $show_empty_sections
																		): void {
																			$commission_access = 1;
																			global $double_check_done_str_all;
																			$double_check_done_str = "";
																			$dt_view_qry = "";
																			if ($show_empty_sections == "Y") {
																				$rev_val = 1;
																			} else {
																				if ($paidunpaid_flg == "Unpaid") {
																					if ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "not_double_chk")) {
																						$dt_view_qry = "SELECT loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.double_checked, loop_transaction_buyer.double_checked_bydate, loop_transaction_buyer.double_checked_by, loop_warehouse.b2bid, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 and loop_transaction_buyer.double_checked = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					} elseif ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "double_chk_complete")) {
																						$dt_view_qry = "SELECT loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.double_checked, loop_transaction_buyer.double_checked_bydate, loop_transaction_buyer.double_checked_by, loop_warehouse.b2bid, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 and loop_transaction_buyer.double_checked = 1 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					} else {
																						$dt_view_qry = "SELECT loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.double_checked, loop_transaction_buyer.double_checked_bydate, loop_transaction_buyer.double_checked_by, loop_warehouse.b2bid, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					}
																				} elseif ($paidunpaid_flg == "Paid") {
																					if ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "not_double_chk")) {
																						$dt_view_qry = "SELECT loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.report_commissions_bydate, loop_transaction_buyer.report_commissions_by, loop_warehouse.company_name AS B,  loop_warehouse.b2bid, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 1 and loop_transaction_buyer.double_checked = 0 AND loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' and (report_commissions_bydate >='" . $date_from . "') AND (report_commissions_bydate <= '" . $date_to . " 23:59:59') GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					} elseif ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "double_chk_complete")) {
																						$dt_view_qry = "SELECT loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.report_commissions_bydate, loop_transaction_buyer.report_commissions_by, loop_warehouse.company_name AS B,  loop_warehouse.b2bid, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 and loop_transaction_buyer.double_checked = 1 AND loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' and (report_commissions_bydate >='" . $date_from . "') AND (report_commissions_bydate <= '" . $date_to . " 23:59:59') GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					} else {
																						$dt_view_qry = "SELECT loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.report_commissions_bydate, loop_transaction_buyer.report_commissions_by, loop_warehouse.company_name AS B,  loop_warehouse.b2bid, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 1 AND loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' and (report_commissions_bydate >='" . $date_from . "') AND (report_commissions_bydate <= '" . $date_to . " 23:59:59') GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					}
																				}
																				//echo $dt_view_qry . "<br>";
																				//$dt_view_qry = "SELECT loop_transaction_buyer.id as I, loop_transaction_buyer.po_employee, loop_transaction_buyer.inv_amount FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.inv_amount > 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.inv_amount > 0 and loop_transaction_buyer.po_employee LIKE '" . $initials . "' GROUP BY loop_transaction_buyer.po_employee";
																				db();
																				$dt_view_res = db_query($dt_view_qry);
																				$rev_val = 0;
																				while ($dt_view_row = array_shift($dt_view_res)) {
																					$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A, method FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
																					db();
																					$payment_qry = db_query($payments_sql);
																					$payment = array_shift($payment_qry);

																					if (number_format($dt_view_row["F"], 2) == number_format($payment["A"], 2) && $dt_view_row["F"] != "" && $dt_view_row["F"] > 0) {
																						//echo $dt_view_row["I"] . " " . number_format($dt_view_row["F"],2) . " " . number_format($payment["A"],2) . "<br>";

																						$rev_val = 1;
																						break;
																					}
																				}
																			}


																			if ($rev_val > 0) {

																				if ($empstatus == "Inactive") {
																					echo "<font size=1>" . $name . " [" . $empstatus . "]</font><br>";
																				} else {
																					echo "<font size=1>" . $name . "</font><br>";
																				}

												?>
        <!---------------------------------------------------------------------------------------->
        <!---------------------------------------------------------------------------------------->
        <!------------------------- READY TO BE PAID COMMISSION DEALS TABLE ---------------------->
        <!---------------------------------------------------------------------------------------->
        <!---------------------------------------------------------------------------------------->

        <table width="1200" cellpadding="0" cellspacing="1">
            <?php if ($paidunpaid_flg == "Unpaid") { ?>
            <tr>
                <td class="style24" colspan=8 style="height: 16px" align="middle"><strong>DEALS TO BE PAID COMMISSION
                        FOR <?php echo $name; ?></strong></td>
            </tr>
            <?php } elseif ($paidunpaid_flg == "Paid") {  ?>
            <tr>
                <td class="style24" colspan=8 style="height: 16px" align="middle"><strong>DEALS WHOSE COMMISSION PAID
                        <?php echo $name; ?></strong></td>
            </tr>
            <?php } elseif ($paidunpaid_flg == "Invoice Not Paid") {  ?>
            <tr>
                <td class="style24" colspan=8 style="height: 16px" align="middle"><strong>DEALS WHOSE INVOICE NOT PAID
                        <?php echo $name; ?></strong></td>
            </tr>
            <?php }  ?>


            <?php

																				$display_row = 1;
																				$Employee = "";
																				$tot_rev = 0;
																				$tot_cost = 0;
																				$first_deal_cnt = 0;
																				$deal_cnt = 0;
																				$tot_profit = 0;
																				$actual_profit = "";
																				$profit = 0;
																				$payment = 0;
																				$commission_tot = 0;
																				if ($paidunpaid_flg == "Unpaid") {
																					if ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "not_double_chk")) {
																						$dt_view_qry = "SELECT loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.virtual_inventory_trans_id, loop_transaction_buyer.po_freight, loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.double_checked, loop_transaction_buyer.double_checked_bydate, loop_transaction_buyer.double_checked_by, loop_warehouse.b2bid, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 and loop_transaction_buyer.double_checked = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					} elseif ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "double_chk_complete")) {
																						$dt_view_qry = "SELECT loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.virtual_inventory_trans_id, loop_transaction_buyer.po_freight, loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.double_checked, loop_transaction_buyer.double_checked_bydate, loop_transaction_buyer.double_checked_by, loop_warehouse.b2bid, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 and loop_transaction_buyer.double_checked = 1 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					} else {
																						$dt_view_qry = "SELECT loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.virtual_inventory_trans_id, loop_transaction_buyer.po_freight, loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.double_checked, loop_transaction_buyer.double_checked_bydate, loop_transaction_buyer.double_checked_by, loop_warehouse.b2bid, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					}
																				} elseif ($paidunpaid_flg == "Paid") {
																					if ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "not_double_chk")) {
																						$dt_view_qry = "SELECT loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.virtual_inventory_trans_id, loop_transaction_buyer.po_freight, loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.report_commissions_bydate, loop_transaction_buyer.report_commissions_by, loop_warehouse.company_name AS B,  loop_warehouse.b2bid, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 1 and loop_transaction_buyer.double_checked = 0 AND loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' and (report_commissions_bydate >='" . $date_from . "') AND (report_commissions_bydate <= '" . $date_to . " 23:59:59') GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					} elseif ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "double_chk_complete")) {
																						$dt_view_qry = "SELECT loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.virtual_inventory_trans_id, loop_transaction_buyer.po_freight, loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.report_commissions_bydate, loop_transaction_buyer.report_commissions_by, loop_warehouse.company_name AS B,  loop_warehouse.b2bid, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 and loop_transaction_buyer.double_checked = 1 AND loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' and (report_commissions_bydate >='" . $date_from . "') AND (report_commissions_bydate <= '" . $date_to . " 23:59:59') GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					} else {
																						$dt_view_qry = "SELECT loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.virtual_inventory_trans_id, loop_transaction_buyer.po_freight, loop_transaction_buyer.customerpickup_ucbdelivering_flg, loop_transaction_buyer.report_commissions_bydate, loop_transaction_buyer.report_commissions_by, loop_warehouse.company_name AS B,  loop_warehouse.b2bid, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 1 AND loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.po_employee LIKE '" . $initials . "' and (report_commissions_bydate >='" . $date_from . "') AND (report_commissions_bydate <= '" . $date_to . " 23:59:59') GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																					}
																					//}elseif ($paidunpaid_flg=="Invoice Not Paid"){
																					//$dt_view_qry = "SELECT loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_warehouse.b2bid, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id left join loop_bol_files on loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and loop_transaction_buyer.commission_paid = 0 and loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.po_employee LIKE '" . $initials . "' GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																				}
																				//echo $dt_view_qry;
																				db();
																				$dt_view_res = db_query($dt_view_qry);
																				while ($dt_view_row = array_shift($dt_view_res)) {

																					if ($dt_view_row["customerpickup_ucbdelivering_flg"] == "1") {
																						$pickup_or_ucb_delivering = "Customer Pick-Up";
																					} else {
																						$pickup_or_ucb_delivering = "";
																					}
																					if ($dt_view_row["customerpickup_ucbdelivering_flg"] == "2") {
																						$pickup_or_ucb_delivering = "UCB Delivering";
																					} else {
																						$pickup_or_ucb_delivering = "";
																					}

																					//
																					$pay = 0;
																					if ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "conf")) {
																						$pay_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments Left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"] . " and loop_transaction_buyer_payments.status=7 and loop_transaction_buyer_payments.amount=loop_transaction_buyer_payments.estimated_cost";
																					} elseif ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "conf_diff_cost")) {
																						$pay_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments Left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"] . " and loop_transaction_buyer_payments.status=7 and loop_transaction_buyer_payments.amount<>loop_transaction_buyer_payments.estimated_cost";
																					} elseif ((isset($_GET["match_confirmed"])) && ($_GET["match_confirmed"] == "not_conf")) {
																						$pay_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments Left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"] . " and loop_transaction_buyer_payments.status=6";
																					} else {
																						$pay_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments Left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
																					}
																					//
																					db();
																					$pay_res = db_query($pay_qry);
																					$cnt_row = tep_db_num_rows($pay_res);
																					if ($cnt_row > 0) {
																						//
																						$writeoffcred = "";
																						//This is the payment Info for the Customer paying UCB
																						$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A, method FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
																						db();
																						$payment_qry = db_query($payments_sql);
																						$payment = array_shift($payment_qry);
																						$writeoffcred = $payment["method"];

																						//This is the payment info for UCB paying the related vendors
																						$vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
																						db();
																						$vendor_qry = db_query($vendor_sql);
																						$vendor = array_shift($vendor_qry);

																						//Info about Shipment
																						$bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $dt_view_row["I"] . "' ORDER BY id DESC";
																						db();
																						$bol_file_res = db_query($bol_file_qry);
																						$bol_file_row = array_shift($bol_file_res);

																						$fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $dt_view_row["I"];
																						db();
																						$fbookresult = db_query($fbooksql);
																						$freightbooking = array_shift($fbookresult);

																						$vendors_paid = 0; //Are the vendors paid
																						$vendors_entered = 0; //Has a vendor transaction been entered?
																						$invoice_paid = 0; //Have they paid their invoice?
																						$invoice_entered = 0; //Has the inovice been entered
																						$signed_customer_bol = 0; 	//Customer Signed BOL Uploaded
																						$courtesy_followup = 0; 	//Courtesy Follow Up Made
																						$delivered = 0; 	//Delivered
																						$signed_driver_bol = 0; 	//BOL Signed By Driver
																						$shipped = 0; 	//Shipped
																						$bol_received = 0; 	//BOL Received @ WH
																						$bol_sent = 0; 	//BOL Sent to WH"
																						$bol_created = 0; 	//BOL Created
																						$freight_booked = 0; //freight booked
																						$sales_order = 0;   // Sales Order entered
																						$po_uploaded = 0;  //po uploaded 

																						//Are all the vendors paid?
																						if ($vendor["B"] == 2 && $vendor["C"] == 2) {
																							$vendors_paid = 1;
																						}

																						//Have we entered a vendor transaction?
																						if ($vendor["A"] > 0) {
																							$vendors_entered = 1;
																						}


																						//Have they paid their invoice?
																						if (number_format($dt_view_row["F"], 2) == number_format($payment["A"], 2) && $dt_view_row["F"] != "") {
																							$invoice_paid = 1;
																						}

																						//Has an invoice amount been entered?
																						if ($dt_view_row["F"] > 0) {
																							$invoice_entered = 1;
																						}

																						if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") {
																							$signed_customer_bol = 1;
																						}	//Customer Signed BOL Uploaded
																						if ($bol_file_row["bol_shipment_followup"] > 0) {
																							$courtesy_followup = 1;
																						}	//Courtesy Follow Up Made
																						if ($bol_file_row["bol_shipment_received"] > 0) {
																							$delivered = 1;
																						}	//Delivered
																						if ($bol_file_row["bol_signed_file_name"] != "") {
																							$signed_driver_bol = 1;
																						}	//BOL Signed By Driver
																						if ($bol_file_row["bol_shipped"] > 0) {
																							$shipped = 1;
																						}	//Shipped
																						if ($bol_file_row["bol_received"] > 0) {
																							$bol_received = 1;
																						}	//BOL Received @ WH
																						if ($bol_file_row["bol_sent"] > 0) {
																							$bol_sent = 1;
																						}	//BOL Sent to WH"
																						if ($bol_file_row["id"] > 0) {
																							$bol_created = 1;
																						}	//BOL Created

																						if ($freightbooking["id"] > 0) {
																							$freight_booked = 1;
																						} //freight booked

																						if (($dt_view_row["G"] == 1)) {
																							$sales_order = 1;
																						} //sales order created
																						if ($dt_view_row["H"] != "") {
																							$po_uploaded = 1;
																						} //po uploaded 



																						$boxsource = "";
																						$box_qry = "SELECT loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments Left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.typeid = 1 AND loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
																						db();
																						$box_res = db_query($box_qry);
																						while ($box_row = array_shift($box_res)) {
																							$boxsource = $box_row["C"];
																						}

																						$display_rec = "no";
																						if ($paidunpaid_flg == "Unpaid" && $invoice_entered == 1 && $invoice_paid == 1) {
																							$display_rec = "yes";
																						}

																						if ($paidunpaid_flg == "Paid" && $invoice_entered == 1 && $invoice_paid == 1) {
																							$display_rec = "yes";
																						}

																						if ($paidunpaid_flg == "Invoice Not Paid" && $invoice_paid == 0) {
																							$display_rec = "yes";
																						}

																						if ($display_rec == "yes") {

																							if (isset($alternate_flg) == "") {
																								$class_color = "newtxttheam_withdot";
																								$alternate_flg = "y";
																							} else {
																								$class_color = "newtxttheam_withdot_light";
																								$alternate_flg = "";
																							}

																							$tot_rev  = $tot_rev + $dt_view_row["F"];

																							$dt_view_qry2 = "SELECT SUM(loop_bol_tracking.qty) AS A, loop_bol_tracking.bol_STL1 AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.warehouse_id AS D, loop_bol_tracking.bol_pickupdate AS E, loop_bol_tracking.quantity1 AS Q1, loop_bol_tracking.quantity2 AS Q2, loop_bol_tracking.quantity3 AS Q3 FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $dt_view_row["I"];
																							db();
																							$dt_view_res2 = db_query($dt_view_qry2);
																							$dt_view_row2 = array_shift($dt_view_res2);

																							$company_name = getnickname($dt_view_row["B"], $dt_view_row["b2bid"]);

																							$pay_method_cc = "black";
																							$class_color_cc_flg = $class_color;
																							if ($writeoffcred == "Credit Card") {
																								$cc_fee_flg = "no";
																								$box_qry = "SELECT * from loop_transaction_buyer_payments where typeid = 8 and transaction_buyer_id = " . $dt_view_row["I"];
																								db();
																								$box_res = db_query($box_qry);
																								while ($box_row = array_shift($box_res)) {
																									$cc_fee_flg = "yes";
																								}

																								if ($cc_fee_flg == "no") {
																									//$pay_method_cc = "white";
																									//$class_color_cc_flg = "newtxttheam_withdot_red";
																								}
																							}

																							$ucb_delv_color_flg = "black";
																							$ucb_delv_flg = $class_color;
																							if ($pickup_or_ucb_delivering == "UCB Delivering") {
																								$freight_flg = "no";
																								$box_qry = "SELECT * from loop_transaction_buyer_payments where typeid = 2 and transaction_buyer_id = " . $dt_view_row["I"];
																								db();
																								$box_res = db_query($box_qry);
																								while ($box_row = array_shift($box_res)) {
																									$freight_flg = "yes";
																								}

																								if ($freight_flg == "no") {
																									//$ucb_delv_color_flg = "white";
																									//$ucb_delv_flg = "newtxttheam_withdot_red";
																								}
																							}

																							$cust_pickup_color_flg = "black";
																							$cust_pickup_flg = $class_color;
																							if ($pickup_or_ucb_delivering == "Customer Pick-Up") {
																								$freight_flg = "yes";
																								$box_qry = "SELECT * from loop_transaction_buyer_payments where typeid = 2 and transaction_buyer_id = " . $dt_view_row["I"];
																								db();
																								$box_res = db_query($box_qry);
																								while ($box_row = array_shift($box_res)) {
																									if ($box_row["estimated_cost"] > 0) {
																										$freight_flg = "no";
																									}
																								}

																								if ($freight_flg == "no") {
																									//$cust_pickup_color_flg = "white";
																									//$cust_pickup_flg = "newtxttheam_withdot_red";
																								}
																							}
															?>
            <tr>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>ID</strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>Company</strong>
                </td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>Source</strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>Invoiced
                        Amount</strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>Payment
                        Method</strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>Freight
                        Method?</strong></td>
            </tr>

            <tr>

                <td bgColor="#e4e4e4" class="<?php echo $class_color; ?>" width="50px" align="center">
                    <?php echo $dt_view_row["I"]; ?>
                </td>
                <td bgColor="#e4e4e4" class="<?php echo $class_color; ?>" width="200px">
                    <a target="_blank"
                        href="viewCompany.php?ID=<?php echo $dt_view_row["b2bid"]; ?>&show=transactions&warehouse_id=<?php echo $dt_view_row["D"]; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $dt_view_row["D"]; ?>&rec_id=<?php echo $dt_view_row["I"]; ?>&display=buyer_invoice"
                        target="_blank">
                        <font size=1><?php echo $company_name; ?></font>
                    </a>
                </td>
                <td bgColor="#e4e4e4" class="<?php echo $class_color; ?>" width="150px">
                    <?php echo $boxsource; ?>
                </td>

                <td bgColor="#e4e4e4" class="<?php echo $class_color; ?>" align="right" width="155px">
                    $<?php echo number_format($dt_view_row["F"], 2); ?>
                </td>
                <td bgColor="#e4e4e4" class="<?php echo $class_color_cc_flg; ?>" align="center" width="170px">
                    <font size=1 color=<?php echo $pay_method_cc; ?>><?php echo $writeoffcred; ?></font>
                </td>
                <?php if ($pickup_or_ucb_delivering == "UCB Delivering") { ?>
                <td bgColor="#e4e4e4" class="<?php echo $ucb_delv_flg; ?>" align="center" width="205px">
                    <font size=1 color=<?php echo $ucb_delv_color_flg; ?>><?php echo $pickup_or_ucb_delivering; ?>
                    </font>
                </td>
                <?php } ?>
                <?php if ($pickup_or_ucb_delivering == "Customer Pick-Up") { ?>
                <td bgColor="#e4e4e4" class="<?php echo $cust_pickup_flg; ?>" align="center" width="205px">
                    <font size=1 color=<?php echo $cust_pickup_color_flg; ?>><?php echo $pickup_or_ucb_delivering; ?>
                    </font>
                </td>
                <?php } ?>



            </tr>

            <tr>
                <td colspan="6">&nbsp;

                </td>
            </tr>

            <tr>
                <td width="1200" colspan="6">
                    <div id="show_rec<?php echo $dt_view_row["I"]; ?>">
                        <table width="1200" cellpadding="0" cellspacing="1">
                            <?php

																						?>
                            <tr>

                                <td style="height: 16px" colspan="2" class="<?php echo $class_color; ?>" align="center">
                                    <strong>Company</strong>
                                </td>
                                <td style="height: 16px" class="<?php echo $class_color; ?>" align="center">
                                    <strong>Type</strong>
                                </td>
                                <td style="height: 16px" colspan="2" class="<?php echo $class_color; ?>" align="center">
                                    <strong>Estimated Cost</strong>
                                </td>

                                <td style="height: 16px" class="<?php echo $class_color; ?>" align="center">
                                    <strong>Employee</strong>
                                </td>
                                <td style="height: 16px" class="<?php echo $class_color; ?>" align="center">
                                    <strong>Date</strong>
                                </td>
                                <td style="height: 16px" class="<?php echo $class_color; ?>" align="center">
                                    <strong>Notes </strong>
                                </td>
                                <?php if ($commission_access == '2' || $commission_access == '3') {
																							} elseif ((isset($_REQUEST["show_e"])) && ($commission_access == '1' && $_REQUEST["show_e"] == 'Y')) {
																							} elseif ((!isset($_REQUEST["show_e"])) && ($commission_access == '1')) { ?>

                                <?php } ?>
                            </tr>
                            <?php

																							//  }
																							$display_row = 0;
																						?>
                            <?php

																							$pay = 0;

																							$pay_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments Left JOIN files_companies ON files_companies.id = loop_transaction_buyer_payments.company_id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  Left JOIN loop_employees ON loop_transaction_buyer_payments.employee_id=loop_employees.id WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];

																							db();
																							$pay_res_new = db_query($pay_qry);
																							$cnt_row = tep_db_num_rows($pay_res_new);
																							$transaction_buyer_id = '';
																							if ($cnt_row > 0) {

																								while ($pay_row = array_shift($pay_res_new)) {
																									$transaction_buyer_id = $dt_view_row["I"];
																									$transaction_buyer_payment_id = $pay_row["A"];
																									//

																						?>

                            <?php /*if ($pay_row["estimated_cost"] != $pay_row["amount"])
	{
		$highlight_row="#df2f2f";
		$highlight_row_text_color="#fff";
		$class_color = "style1_org_white";
	}
	else{
		$highlight_row="#e4e4e4";
		$highlight_row_text_color = "#000";
		$class_color = "style1_org";
	}*/
																									$highlight_row = "#e4e4e4";
																									$highlight_row_text_color = "#000";
																									//$class_color = "style1_org";

																									if ($pay_row["amount"] == 0) {
																										//	$highlight_row="#faff90";
																									}

																									$freight_class_color = $class_color;
																									$freight_class_color_bg = "black";
																									if ($pay_row["typename"] == "Freight" && ($pickup_or_ucb_delivering == "UCB Delivering")) {
																										$booked_delivery_cost = 0;
																										db();
																										$res_new_tmp = db_query("Select booked_delivery_cost from loop_transaction_buyer_freightview where trans_rec_id = " . $dt_view_row["I"]);
																										while ($row_data_tmp = array_shift($res_new_tmp)) {
																											$booked_delivery_cost = $row_data_tmp["booked_delivery_cost"];
																										}

																										if ($booked_delivery_cost != $pay_row["estimated_cost"]) {
																											//$freight_class_color = "newtxttheam_withdot_red";
																											//$freight_class_color_bg = "white";
																										}
																									}

																									$box_cost_class_color = $class_color;
																									$box_cost_class_color_bg = "black";
																									$box_cost = 0;
																									if ($pay_row["typename"] == "Boxes" && ($dt_view_row["virtual_inventory_company_id"] != -1)) {
																										$box_cost = 0;
																										$goodvalue = 0;
																										//$dt_view_row
																										db();
																										$res_new_tmp = db_query("Select * from loop_boxes_sort where trans_rec_id = " . $dt_view_row["virtual_inventory_trans_id"]);
																										while ($row_data_tmp = array_shift($res_new_tmp)) {
																											$goodvalue = $goodvalue + ($row_data_tmp["boxgood"] * $row_data_tmp["sort_boxgoodvalue"]);
																										}
																										//$box_cost = number_format($goodvalue + $badvalue + $dt_view_tran_row["freightcharge"] + $dt_view_tran_row["othercharge"],2)
																										$box_cost = $goodvalue;

																										if ($box_cost != $pay_row["estimated_cost"]) {
																											//$box_cost_class_color = "newtxttheam_withdot_red";
																											//$box_cost_class_color_bg = "white";
																										}
																									}

																								?>
                            <tr id="buyer_row<?php echo $pay_row["A"]; ?>">

                                <!--<td align="center" valign="top" height="13" class="style1" bgColor="<?php echo $highlight_row; ?>">
			<?php if ($pay_row["B"] == 0) echo " Open "; ?> 
			 <?php if ($pay_row["B"] == 1) echo " Invoiced "; ?> 
			 <?php if ($pay_row["B"] == 2) echo " Paid "; ?> 
			 <?php if ($pay_row["B"] == 3) echo " Other "; ?> 
			 <?php if ($pay_row["B"] == 4) echo " Remove "; ?> 
			 
			 <?php if ($pay_row["B"] == 5) echo " <font color:yellow>Estimated</font> "; ?> 
			<?php if ($pay_row["B"] == 7) echo " Confirmed "; ?> 
			<?php if ($pay_row["B"] == 6) echo " Not Confirmed "; ?> 
		</td>-->

                                <td align="left" height="13" width="350px" colspan="2"
                                    class="<?php echo $class_color; ?>">
                                    <Font size='1' Face="arial"><?php echo $pay_row["C"]; ?></font>&nbsp;
                                </td>

                                <?php if ($pay_row["typename"] == "Boxes" && ($dt_view_row["virtual_inventory_company_id"] != -1)) {
																										$freight_class_color = $box_cost_class_color;
																										$freight_class_color_bg = $box_cost_class_color_bg;
																									} ?>

                                <td align="center" height="13" width="115px"
                                    class="<?php echo $freight_class_color; ?>">
                                    <font size=1 color=<?php echo $freight_class_color_bg; ?>>
                                        <?php echo $pay_row["typename"]; ?></font>
                                </td>

                                <td align="right" height="13" width="165px" colspan="2"
                                    class="<?php echo $class_color; ?>">
                                    $<?php echo number_format($pay_row["estimated_cost"], 2);
																											//$pay = $pay + $pay_row["estimated_cost"];
																											//$tot_cost = $tot_cost + $pay_row["estimated_cost"];
																											?></td>

                                <!-- <td align="right" height="13" width="180px" class="<?php echo $class_color; ?>" bgColor="<?php echo $highlight_row; ?>"> -->
                                <?php
																									if ($pay_row["amount"] != 0) {
																										//echo "$" . number_format($pay_row["amount"],2); 
																									}
																									$pay = $pay + $pay_row["estimated_cost"];
																									$tot_cost = $tot_cost + $pay_row["estimated_cost"];
																									//echo $name . " " . $tot_cost . "<br>"; 
																									?>
                </td>

                <td align="center" height="13" width="55px" class="<?php echo $class_color; ?>">

                    <?php echo $pay_row["initials"]; ?></td>

                <td align="center" height="13" width="60px" class="<?php echo $class_color; ?>">

                    <?php echo $pay_row["date"]; ?></td>

                <td align="left" height="13" style="width: 270px" class="<?php echo $class_color; ?>">

                    <?php echo $pay_row["notes"]; ?></td>
                <?php if ($commission_access == '2' || $commission_access == '3') {
																										echo "";
																									} elseif ((isset($_REQUEST["show_e"])) && ($commission_access == '1' && $_REQUEST["show_e"] == 'Y')) {
																										echo "";
																									} elseif ((!isset($_REQUEST["show_e"])) && ($commission_access == '1')) {
																										//if($commission_access==1){ 
																			?>

                <!-- 
		<td class="style12" style="height: 16px" align="middle" bgColor="<?php echo $highlight_row; ?>"><a href='#' id="<?php echo $transaction_buyer_payment_id; ?>" onclick="edit_comm_row('<?php echo $transaction_buyer_payment_id; ?>','<?php echo $transaction_buyer_id; ?>','<?php echo $paidunpaid_flg; ?>', '<?php echo $_REQUEST["eid"]; ?>'); return false;">Edit</a> | <a href='#' id="del<?php echo $transaction_buyer_payment_id; ?>" onclick="delete_comm_row('<?php echo $transaction_buyer_payment_id; ?>','<?php echo $transaction_buyer_id; ?>','<?php echo $paidunpaid_flg; ?>', '<?php echo $eid; ?>'); return false;">Delete</a></td>
		-->

                <?php
																									} //end else if
																			?>


            </tr>

            <?php }  //while loop for vendor payments

																								$double_check = "no";
																								$double_check_done = "no";
																	?>
            <tr>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong></strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong></strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong></strong></td>
                <td class="<?php echo $class_color; ?>" align="right">Profit
                </td>
                <td class="<?php echo $class_color; ?>" align="right">
                    <strong>
                        <div id="val_profit<?php echo $transaction_buyer_id; ?>">
                            $<?php echo number_format($dt_view_row["F"] - $pay, 2); ?>
                        </div>
                    </strong>
                </td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>&nbsp;</strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>&nbsp;</strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle">
                    <?php


																			?>
                </td>
                <?php if ($commission_access == 2 || $commission_access == 3) {
																								} elseif ((isset($_REQUEST["show_e"])) && ($commission_access == '1' && $_REQUEST["show_e"] == 'Y')) {
																								} elseif ((!isset($_REQUEST["show_e"])) && ($commission_access == '1')) {
																									//if($commission_access==1){ 
																		?>

                <?php } ?>
            </tr>
            <?php if ($commission_access == 2) {
																								}
																								if ((!isset($_REQUEST["show_e"])) && ($commission_access == '1')) { ?>
            <tr>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong></strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>
                        <?php
																									if ($writeoffcred == "Write-off" || $writeoffcred == "Credit") {
																										echo "<span style='color:#FC0004;'><b>WRITE OFF</b>";
																									}
																					?>
                    </strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"></td>

                <td class="<?php echo $class_color; ?>" style="height: 16px" align="right">
                    <font size='1'>Commission</font>
                </td>
                <?php
																									$fdq = "SELECT id AS I FROM loop_transaction_buyer WHERE warehouse_id = " . $dt_view_row["D"] . " ORDER BY I ASC LIMIT 0,1";
																									db();
																									$first_deal = '';
																									$fd_res = db_query($fdq);
																									$fd_row = array_shift($fd_res);
																									$first_deal_flg = "n";
																									if ($fd_row["I"] == $dt_view_row["I"]) {
																										$first_deal = 0;
																										$first_deal_flg = "y";
																										$first_deal_cnt = $first_deal_cnt + 1;
																									} else {
																										$first_deal = 0;
																									}

																									$tmp_comm = ($commission / 100) * ($dt_view_row["F"] - $pay);
																									if ($tmp_comm < 0) {
																										$tmp_comm = $first_deal;
																									} else {
																										$tmp_comm = $tmp_comm + $first_deal;
																									}
																			?>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="right">
                    <font size='1' Face="arial">
                        <strong>
                            <div id="val_commission<?php echo $transaction_buyer_id; ?>">
                                <font size='1'>$<?php echo number_format($tmp_comm, 2); ?></font>
                            </div>
                        </strong>
                    </font>

                </td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle">
                    <strong><?php if ($first_deal_flg == "y") echo "First Load!"; ?></strong>
                </td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>&nbsp;</strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle">
                    <?php
																									if ($double_check == "no") {
																									}
																				?>
                </td>
            </tr>
            <?php
																								} //end user access 1
																	?>
            <?php
																								if (($commission_access == 3) || ((isset($_REQUEST["show_e"])) && ($commission_access == '1' && $_REQUEST["show_e"] == 'Y'))) { ?>
            <tr>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong></strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>
                        <?php
																									if ($writeoffcred == "Write-off" || $writeoffcred == "Credit") {
																										echo "<span style='color:#FC0004;'><b>WRITE OFF</b>";
																									}
																					?>
                    </strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"></td>

                <td class="<?php echo $class_color; ?>" style="height: 16px" align="right">
                    <font size='1' face="arial"><strong>Commission</strong></font>
                </td>
                <?php
																									$fdq = "SELECT id AS I FROM loop_transaction_buyer WHERE warehouse_id = " . $dt_view_row["D"] . " ORDER BY I ASC LIMIT 0,1";
																									db();
																									$fd_res = db_query($fdq);
																									$fd_row = array_shift($fd_res);
																									$first_deal_flg = "n";
																									$first_deal = '';
																									if ($fd_row["I"] == $dt_view_row["I"]) {
																										$first_deal = 0;
																										$first_deal_flg = "y";
																										$first_deal_cnt = $first_deal_cnt + 1;
																									} else {
																										$first_deal = 0;
																									}

																									$tmp_comm = ($commission / 100) * ($dt_view_row["F"] - $pay);
																									if ($tmp_comm < 0) {
																										$tmp_comm = $first_deal;
																									} else {
																										$tmp_comm = $tmp_comm + $first_deal;
																									}
																			?>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="right">
                    <font size='1' Face="arial">
                        <strong>
                            <div id="val_commission<?php echo $transaction_buyer_id; ?>">
                                $<?php echo number_format($tmp_comm, 2); ?>
                            </div>
                        </strong>
                    </font>

                </td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong><?php //if ($first_deal_flg == "y") echo "First Load!";
																																								?></strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle"><strong>&nbsp;</strong></td>
                <td class="<?php echo $class_color; ?>" style="height: 16px" align="middle">
                    <?php /*if($paidunpaid_flg=="Unpaid"){ ?>
                    <strong><a
                            href="report_commissions_mark_as_paid.php?unpaid=no&id=<?php echo $dt_view_row["I"];?>">Mark
                            As Paid</a></strong>
                    <?php } elseif ($paidunpaid_flg=="Paid"){  ?>
                    <strong><a
                            href="report_commissions_mark_as_paid.php?unpaid=yes&id=<?php echo $dt_view_row["I"];?>">Mark
                            As UnPaid</a></strong><br />
                    Commission paid by
                    <?php echo $dt_view_row["report_commissions_by"] . " on " . $dt_view_row["report_commissions_bydate"] ?>
                    <?php } */ ?>
                </td>
                <!--<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"></td>-->
            </tr>
            <?php
																								} //end user access 3
																							}
																?>
        </table>
    </div>
    </td>
    </tr>
    <tr>
        <td>
            <font size=2>&nbsp;</font>
        </td>
    </tr>


    <?php
																							$total_profit = 0;
																							if ($dt_view_row["F"] > $pay) {
																								$total_profit = $total_profit + ($dt_view_row["F"] - $pay);
																							} else {
																								$total_profit = '';
																							}

																							$commission_tot_tmp = ($commission / 100) * ($dt_view_row["F"] - $pay);
																							if ($commission_tot_tmp < 0) {
																								$commission_tot_tmp = isset($first_deal);
																							} else {
																								$commission_tot_tmp = $commission_tot_tmp + isset($first_deal);
																							}

																							$deal_cnt = $deal_cnt + 1;

																							if ($commission_tot_tmp > 0) {
																								$commission_tot = $commission_tot + $commission_tot_tmp;
																							}
																						}
																					}	//if not paid
																				}	//while loop

																				$profit_margin = (isset($total_profit) * 100) / $tot_rev;
																				$profit_margin = number_format($profit_margin, 2) . "%";
																				$classnm = "newtxttheam_withdot";
								?>

    </table>

    <?php
																				if ($double_check_done_str != "") {
																					$double_check_done_str = substr($double_check_done_str, 0, strlen($double_check_done_str) - 1);
								?>
    <form name="frm_mark_as_paid" id="frm_mark_as_paid" action="report_commissions-dash.php" method="post">
        <!--<input type="hidden" name="eid" id="eid" value="<?php //echo $_REQUEST["eid"];
																							?>"/>-->
        <input type="hidden" name="match_confirmed" id="match_confirmed"
            value="<?php echo $_REQUEST["match_confirmed"]; ?>" />
        <input type="hidden" name="date_from" id="date_from" value="<?php echo $_REQUEST["date_from"]; ?>" />
        <input type="hidden" name="date_to" id="date_to" value="<?php echo $_REQUEST["date_to"]; ?>" />
        <input type="hidden" name="reprun" id="reprun" value="<?php echo $_REQUEST["reprun"]; ?>" />
        <input type="hidden" name="paidunpaid_flg" id="paidunpaid_flg"
            value="<?php echo $_REQUEST["paidunpaid_flg"]; ?>" />

        <input type="hidden" name="hd_mark_all_paid" id="hd_mark_all_paid"
            value="<?php echo $double_check_done_str; ?>" />
        <?php //echo "Below button will update Mark as Paid for Transcation Ids - " . $double_check_done_str;
										?><br>
        <!--<input type="submit" name="btn_mark_all_paid" id="btn_mark_all_paid" value="Mark All as Paid"/>-->
    </form>
    <?php } ?>

    <?php

																				if ($commission_access == '1' || $commission_access == '3') {
								?>
    <table>
        <tr style="background: #2B1B17;">
            <td colspan="7" style="color:#ffffff" align="center">
                <font size=1>
                    <?php
																					if ($empstatus == "Inactive") {
																						echo "<font size=1>" . $name . " [" . $empstatus . "]</font>&nbsp;";
																					} else {
																						echo "<font size=1>" . $name . "</font>&nbsp;";
																					}
													?>
                    Summary</font>
            </td>
        </tr>
        <?php if ((!isset($_REQUEST["show_e"])) && ($commission_access == '1')) {
										?>
        <tr style="background: #2B1B17;">
            <td style="color:#ffffff" align="middle">
                <font size="1">Chks Received</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total COGS</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total Gross Profit</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Avg Gross Profit Margin</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total Commission</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total # of Chks Received</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total # of Chks are First Deals</font>
            </td>
        </tr>
        <?php
																					}
										?>
        <?php if (($commission_access == '3') || ((isset($_REQUEST["show_e"])) && ($commission_access == '1' && $_REQUEST["show_e"] == 'Y'))) {
										?>
        <tr style="background: #2B1B17;">
            <td style="color:#ffffff" align="middle">
                <font size="1">Total Revenue</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total cost</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total Profit</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total Profit margin</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total Commission</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total number of deals</font>
            </td>
            <td style="color:#ffffff" align="middle">
                <font size="1">Total number of first deals</font>
            </td>
        </tr>
        <?php
																					}
										?>
        <tr id="summary_row">

            <td align="right" class="<?php echo $classnm ?>"><strong>$<?php echo number_format($tot_rev, 2); ?></strong>
            </td>
            <td align="right" class="<?php echo $classnm ?>">
                <strong>$<?php echo number_format($tot_cost, 2); ?></strong>
            </td>
            <td align="right" class="<?php echo $classnm ?>">
                <strong>$<?php echo number_format($total_profit, 2); ?></strong>
            </td>
            <td align="right" class="<?php echo $classnm ?>"><strong><?php echo $profit_margin; ?></strong></td>
            <td align="right" class="<?php echo $classnm ?>">
                <strong>$<?php echo number_format($commission_tot, 2); ?></strong>
            </td>
            <td align="right" class="<?php echo $classnm ?>"><strong><?php echo $deal_cnt; ?></strong></td>
            <td align="right" class="<?php echo $classnm ?>"><strong><?php echo $first_deal_cnt; ?></strong></td>

        </tr>
    </table>
    <?php
																				} //end commission access if 
								?>
    <br>

    <?php
																				echo "<hr>";
																			}
																		}
						?>

    <?php

																		if (isset($_REQUEST["reprun"])) {
																			showempdetails($_GET["paidunpaid_flg"], $_GET["date_from"], $_GET["date_to"], $name, $initials, $commission, $empstatus, $commission_access, $_REQUEST["show_empty_sections"]);
																		}
						?>
    <br>

    <br />
    <?php
																	} else {
																	}

					?>
    </td>
    </tr>

    <?php } //Added for search bar in separate tr
				?>
    </table>

    <?php

				function showtodolist(): void
				{

				?>

    <table width="700" border="0" cellspacing="1" cellpadding="1">
        <tr align="center">
            <td colspan="8" bgcolor="#C0CDDA">
                <font face="Arial, Helvetica, sans-serif" size="1">Active Tasks</font>
            </td>
        </tr>
        <tr align="center">
            <td bgcolor="#C0CDDA">
                <font size="1">Company</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Task Name</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Created By</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Assigned To</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Created On</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Due Date</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Priority</font>
            </td>
            <td bgcolor="#C0CDDA">&nbsp;

            </td>
        </tr>
        <?php
						$sql = "SELECT * FROM todolist where assign_to = '" . $_COOKIE["userinitials"] . "' and status = 1 order by due_date";
						db();
						$result = db_query($sql);
						while ($myrowsel = array_shift($result)) {
							$date1 = new DateTime($myrowsel["due_date"]);
							$date2 = new DateTime();

							$days = (strtotime($myrowsel["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);

						?>
        <tr align="center">
            <td bgcolor="#E4E4E4" align="left">
                <font size="1"><a target="_blank"
                        href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $myrowsel["companyid"]; ?>'><?php echo getnickname('', $myrowsel["companyid"]); ?></a>
                </font>
            </td>
            <td bgcolor="#E4E4E4" align="left">
                <font size="1"><?php echo $myrowsel["task_name"] ?></font>
            </td>
            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo $myrowsel["task_created_by"] ?></font>
            </td>
            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo $myrowsel["assign_to"] ?></font>
            </td>

            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo date("m/d/Y H:i:s", strtotime($myrowsel["task_added_on"]))  . " CT"; ?></font>
            </td>

            <?php if ($days == 0) { ?>
            <td bgcolor="green">
                <font size="1"><?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?></font>
            </td>
            <?php }

								if ($days > 0) { ?>
            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?></font>
            </td>
            <?php }

								if ($days < 0) { ?>
            <td bgcolor="red">
                <font size="1"><?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?></font>
            </td>
            <?php } ?>
            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo $myrowsel["task_priority"] ?></font>
            </td>
            <td bgcolor="#E4E4E4" align="middle">
                <input type="button" value="Mark Complete" name="todo_markcompl" id="todo_markcompl"
                    onclick="todoitem_markcomp_dash(<?php echo $myrowsel["unqid"] ?>)">
            </td>
        </tr>
        <?php
						}
						?>
    </table>

    <br>
    <table width="700" border="0" cellspacing="1" cellpadding="1">
        <tr align="center">
            <td colspan="7" bgcolor="#C0CDDA">
                <font face="Arial, Helvetica, sans-serif" size="1">Recently Completed Tasks</font>
            </td>
        </tr>
        <tr align="center">
            <td bgcolor="#C0CDDA">
                <font size="1">Company</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Task Name</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Assigned To</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Created On</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Due Date</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Priority</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Completed by and On</font>
            </td>
        </tr>
        <?php
						$sql = "SELECT * FROM todolist where assign_to = '" . $_COOKIE["userinitials"] . "' and status = 2 order by due_date desc limit 5";
						db();
						$result = db_query($sql);
						while ($myrowsel = array_shift($result)) {
						?>
        <tr align="center">
            <td bgcolor="#E4E4E4" align="left">
                <font size="1"><a target="_blank"
                        href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $myrowsel["companyid"]; ?>'><?php echo getnickname('', $myrowsel["companyid"]); ?></a>
                </font>
            </td>
            <td bgcolor="#E4E4E4" align="left">
                <font size="1"><?php echo $myrowsel["task_name"] ?></font>
            </td>
            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo $myrowsel["assign_to"] ?></font>
            </td>

            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo date("m/d/Y H:i:s", strtotime($myrowsel["task_added_on"]))  . " CT"; ?></font>
            </td>

            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?></font>
            </td>
            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo $myrowsel["task_priority"] ?></font>
            </td>
            <td bgcolor="#E4E4E4">
                <font size="1">
                    <?php echo $myrowsel["mark_comp_by"] . " " . date("m/d/Y", strtotime($myrowsel["mark_comp_on"])) . " CT"; ?>
                </font>
            </td>
        </tr>
        <?php
						}
						?>
        <tr align="center">
            <td colspan="7" bgcolor="#C0CDDA">
                <input type="button" id="todoshowall"
                    onclick="todoitem_showall('<?php echo $_COOKIE["userinitials"]; ?>')" value="Show All" />
            </td>
        </tr>
    </table>
    <?php
				}
				?>

    <!------------------------ END NEW DASHBOARD ------------>
    <?php
				function showCustomerList(): void
				{


					if ($_REQUEST["so"] == "A") {
						$so = "D";
					} else {
						$so = "A";
					}

				?>
    <div><i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of the report, before
            using the sort option.</i></div>
    <table>
        <tr>
            <td width="5%" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=dt&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">DATE</a>
                </font>
            </td>
            <td width="5%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=age&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">AGE</a>
                </font>
            </td>
            <td width="10%" bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=contact&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">CONTACT</a>
                </font>
            </td>
            <td width="21%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=cname&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">COMPANY
                        NAME</a></font>
            </td>
            <td width="21%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=status&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">STATUS</a>
                </font>
            </td>
            <td width="8%" bgcolor="#D9F2FF">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=city&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">CITY</a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=state&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">STATE</a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=zip&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">ZIP</a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ns&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Next
                        Step</a></font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=lc&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Last<br>Communication</a>
                </font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nd&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Next
                        Communication</font>
            </td>

            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nooftrans&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">#
                        of transactions</font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=totrev&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Total
                        Revenue</font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=totprofit&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Total
                        Profit</font>
            </td>
            <td bgcolor="#D9F2FF" align="center">
                <font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a
                        href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=profitmargin&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Profit
                        margin</font>
            </td>
        </tr>

        <?php
						$sql = "Select companyInfo.id AS I, companyInfo.status, companyInfo.last_contact_date, companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.assignedto = " . $_COOKIE['b2b_id'] . " and companyInfo.loopid > 0 and companyInfo.active = 1 ";
						$sql = $sql . " GROUP BY companyInfo.id ";
						//echo "<br/>" . $sql . "<br/><br/>";
						db_b2b();
						$MGArray = array();
						$data_res = db_query($sql);
						while ($data = array_shift($data_res)) {
							$sqlmtd = "SELECT SUM(inv_amount) AS totrevenue, count(loop_transaction_buyer.id) as nooftrans FROM loop_transaction_buyer WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 1 and loop_transaction_buyer.ignore < 1 AND loop_transaction_buyer.warehouse_id = " . $data["LID"];
							db();
							$data_1 = db_query($sqlmtd);
							$totrevenue = 0;
							$nooftrans = 0;
							while ($datars_new = array_shift($data_1)) {
								$totrevenue = $datars_new["totrevenue"];
								$nooftrans = $datars_new["nooftrans"];
							}

							$sqlmtd = "SELECT SUM(inv_amount) AS SUMPO FROM loop_transaction_buyer WHERE shipped = 1 and inv_entered = 1 and commission_paid = 1 and loop_transaction_buyer.ignore < 1 AND loop_transaction_buyer.warehouse_id = " . $data["LID"];
							db();
							$resultmtd = db_query($sqlmtd);
							$summtd_1 = 0;
							while ($summtd = array_shift($resultmtd)) {
								$summtd_1 = $summtd["SUMPO"];
							}

							$sqlmtd = "SELECT SUM(amount) AS sum_amt from loop_transaction_buyer_payments INNER JOIN loop_transaction_buyer ON loop_transaction_buyer_payments.transaction_buyer_id=loop_transaction_buyer.id WHERE loop_transaction_buyer.ignore < 1 and shipped = 1 and inv_entered = 1 and commission_paid = 1 and loop_transaction_buyer.warehouse_id = " . $data["LID"];
							db();
							$resultmtd = db_query($sqlmtd);
							$summtd_SUMPO = 0;
							$emp_yr_grossprf_tot = 0;
							$profit_margin = 0;
							while ($summtd = array_shift($resultmtd)) {
								if ($summtd_1 > $summtd["sum_amt"]) {
									$summtd_SUMPO = $summtd_1 - $summtd["sum_amt"];
									$emp_yr_grossprf_tot = $emp_yr_grossprf_tot + $summtd_SUMPO;
								}
								$profit_margin = ($summtd_SUMPO * 100) / $summtd_1;
								$profit_margin = number_format($profit_margin, 2) . "%";
							}



							$tmp_msg_dt = date('Y-m-d', strtotime($data['last_contact_date']));

							$status_name = "";
							$qry = "select name from status where id=" . $data['status'];
							db_b2b();
							$dt_view_res = db_query($qry);
							while ($myrow = array_shift($dt_view_res)) {
								$status_name = $myrow['name'];
							}

							$MGArray[] = array(
								'dateval' => $data["D"], 'status' => $status_name, 'age' => $data["D"], 'contact' => $data["C"], 'LID' => $data["LID"], 'I' => $data["I"],
								'company' => $data["CO"], 'companynn' => $data["NN"], 'phone' => $data["PH"], 'city' => $data["CI"],
								'state' => $data["ST"], 'zip' => $data["ZI"], 'nextstep' => $data["NS"], 'lastcomm' => $tmp_msg_dt, 'nextcomm' => $data["ND"],
								'assgnto' => $data["EI"], 'nooftrans' => $nooftrans, 'totrev' => $totrevenue, 'totprofit' => $emp_yr_grossprf_tot, 'profitmargin' => $profit_margin
							);
						}

						//$_SESSION['sortarrayn'] = $MGArray;

						$sort_order_pre = "ASC";
						$sort_order_arrtxt = "SORT_ASC";
						if ($_REQUEST["so"] != "") {
							if ($_REQUEST["so"] == "A") {
								$sort_order_pre = " ASC";
								$sort_order_arrtxt = "SORT_ASC";
							} else {
								$sort_order_pre = " DESC";
								$sort_order_arrtxt = "SORT_DESC";
							}
						} else {
							$sort_order_pre = " DESC";
							$sort_order_arrtxt = "SORT_DESC";
						}

						if (isset($_REQUEST["sk"])) {
							//$MGArray = $_SESSION['sortarrayn'];

							$MGArraysort_I = array();

							if ($_REQUEST["sk"] == "dt" || $_REQUEST["sk"] == "age") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['dateval'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "contact") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['contact'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "status") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['status'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "cname") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['company'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "phone") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['phone'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "city") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['city'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "state") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['state'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "zip") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['zip'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "ns") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['nextstep'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "lc") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['lastcomm'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "nd") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['nextcomm'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "ei") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['assgnto'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "nooftrans") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['nooftrans'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "totrev") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['totrev'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "totprofit") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['totprofit'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
								}
							}
							if ($_REQUEST["sk"] == "profitmargin") {
								foreach ($MGArray as $MGArraytmp) {
									$MGArraysort_I[] = $MGArraytmp['profitmargin'];
								}
								if ($sort_order_arrtxt == "SORT_ASC") {
									array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
								} else {
									array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
								}
							}
						} else {

							//$MGArray = $_SESSION['sortarrayn'];
							$MGArraysort_I = array();

							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort_I[] = $MGArraytmp['dateval'];
							}
							array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
						}

						foreach ($MGArray as $MGArraytmp2) {
						?>
        <tr valign="middle">
            <td width="5%" bgcolor="#E4E4E4">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo  timestamp_to_datetime($MGArraytmp2["dateval"]);
																																						?></font>
            </td>
            <td width="5%" bgcolor="#E4E4E4">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo date_diff_new($MGArraytmp2["dateval"], "NOW");
																																						?> Days</font>
            </td>
            <td width="10%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["contact"] ?></font>
            </td>
            <td width="21%" bgcolor="#E4E4E4"><a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $MGArraytmp2["I"] ?>">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php if ($MGArraytmp2["companynn"] != "") echo $MGArraytmp2["companynn"];
																								else echo $MGArraytmp2["company"] ?><?php if ($MGArraytmp2["LID"] > 0) echo "</b>"; ?></font>
                </a></td>
            <td width="10%" bgcolor="#E4E4E4" align="left">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["status"]; ?>
                </font>
            </td>
            <td width="3%" bgcolor="#E4E4E4">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["phone"] ?></font>
            </td>
            <td width="10%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["city"] ?></font>
            </td>
            <td width="5%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["state"] ?></font>
            </td>
            <td width="5%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["zip"] ?></font>
            </td>
            <td width="15%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["nextstep"] ?></font>
            </td>
            <td width="10%" bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php if ($MGArraytmp2["lastcomm"] != "") echo date('m/d/Y', strtotime($MGArraytmp2["lastcomm"])); ?>
            <td width="10%" <?php if ($MGArraytmp2["nextcomm"] == date('Y-m-d')) { ?> bgcolor="#00FF00"
                <?php } elseif ($MGArraytmp2["nextcomm"] < date('Y-m-d') && $MGArraytmp2["nextcomm"] != "") { ?>
                bgcolor="#FF0000" <?php } else { ?> bgcolor="#E4E4E4" <?php } ?> align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php if ($MGArraytmp2["nextcomm"] != "") echo date('m/d/Y', strtotime($MGArraytmp2["nextcomm"])); ?>
                </font>
            </td>
            <td bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo $MGArraytmp2["nooftrans"] ?></font>
            </td>
            <td bgcolor="#E4E4E4" align="right">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    $<?php echo number_format($MGArraytmp2["totrev"], 2) ?></font>
            </td>
            <td bgcolor="#E4E4E4" align="right">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    $<?php echo number_format($MGArraytmp2["totprofit"], 2) ?></font>
            </td>
            <td bgcolor="#E4E4E4" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo $MGArraytmp2["profitmargin"] ?></font>
            </td>
        </tr>

        <?php
						} ?>
    </table>

    <div><i>
            <font color="red">"END OF REPORT"</font>
        </i></div>
    <?php
				}


				function sales_quotas(string $initials): void
				{
					$sql = "SELECT * FROM loop_employees where initials = '$initials'";
					db();
					$result = db_query($sql);
					while ($rowemp = array_shift($result)) {
					?>

    <table cellSpacing="1" cellPadding="1" border="0" width="500">
        <tr>
            <td bgColor='#ABC5DF' colspan="5" align="center"><strong>Month wise Details</strong></td>
        </tr>
        <tr>
            <td bgColor='#ABC5DF' align="center"><strong>Month</strong></td>
            <td bgColor='#ABC5DF' align="center"><strong>Quota</strong></td>
            <td bgColor='#ABC5DF' align="center"><strong>Quota to Date</strong></td>
            <td bgColor='#ABC5DF' align="center"><strong>Actual To Date</strong></td>
            <td bgColor='#ABC5DF' align="center"><strong>Percentage</strong></td>
        </tr>

        <?php
							for ($monthcnt = 1; $monthcnt <= date('m'); $monthcnt++) {
								$month_quota = 0;
								$loop_emp_id = $rowemp["id"];
								db();
								$result_q = db_query("Select quota from employee_quota where emp_id = " . $loop_emp_id . " and quota_year = " . date("Y") . " and quota_month = " . $monthcnt);
								while ($rowemp_q = array_shift($result_q)) {
									$month_quota = $rowemp_q["quota"];
								}

								$actual_rev_mtd = 0;
								//$sqlmtd = "SELECT SUM(inv_amount) AS invamt FROM loop_transaction_buyer WHERE po_employee LIKE '" . $rowemp["initials"] . "' and loop_transaction_buyer.ignore < 1 AND inv_amount > 0 and STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . date("Y-" . $monthcnt . "-01") . "' AND '" . date("Y-" . $monthcnt . "-t") . "'";
								$sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . date("Y-" . $monthcnt . "-01") . "' AND '" . date("Y-" . $monthcnt . "-t") . " 23:59:59'";
								db();
								$resultmtd = db_query($sqlmtd);
								while ($summtd = array_shift($resultmtd)) {
									$finalpaid_amt = 0;
									db();
									$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]);
									while ($summtd_finalpmt = array_shift($result_finalpmt)) {
										$finalpaid_amt = $summtd_finalpmt["amt"];
									}

									$inv_amt_totake = 0;
									/*if ($finalpaid_amt > 0){
						if ($summtd["invsent_amt"] > 0){
							if ($finalpaid_amt < $summtd["invsent_amt"]){
								$inv_amt_totake= $summtd["invsent_amt"];
							}else{
								$inv_amt_totake= $finalpaid_amt;
							}
						}else{
							if ($finalpaid_amt < $summtd["inv_amount"]){
								$inv_amt_totake= $summtd["inv_amount"];
							}else{
								$inv_amt_totake= $finalpaid_amt;
							}
						}
					}
					if ($inv_amt_totake == 0 && $summtd["inv_amount"] > 0){
						if ($summtd["invsent_amt"] < $summtd["inv_amount"]){
							$inv_amt_totake= $summtd["inv_amount"];
						}else{
							$inv_amt_totake= $summtd["invsent_amt"];
						}				
					}
					if ($inv_amt_totake == 0 && $summtd["invsent_amt"] > 0){
						$inv_amt_totake= $summtd["invsent_amt"];
					}	*/

									if ($finalpaid_amt > 0) {
										$inv_amt_totake = $finalpaid_amt;
									}
									if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
										$inv_amt_totake = $summtd["invsent_amt"];
									}
									if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
										$inv_amt_totake = $summtd["inv_amount"];
									}

									$actual_rev_mtd = $actual_rev_mtd + $inv_amt_totake;
								}

								if ($monthcnt == date('m')) {
									$quota_mtd = (date("d") * $month_quota) / date("t");
								} else {
									$quota_mtd = (date("t", strtotime(date("Y-" . $monthcnt . "-01"))) * $month_quota) / date("t", strtotime(date("Y-" . $monthcnt . "-01")));
								}
								//echo "quota_mtd : " . date("d") . " " . date("d", strtotime(date("Y-" . $monthcnt . "-01"))) . " " . date("t", strtotime(date("Y-" . $monthcnt . "-01"))) . " " . $quota_mtd . "<br>";
								if ($actual_rev_mtd >= $quota_mtd) {
									$color = "green";
								} elseif ($actual_rev_mtd < $quota_mtd) {
									$color = "red";
								} else {
									$color = "black";
								};

								echo "<tr><td bgColor='#E4EAEB' align ='left'>" . date("F", mktime(0, 0, 0, $monthcnt, 10)) . "</td>";
								echo "<td bgColor='#E4EAEB' align = 'right'>$" . number_format($month_quota, 0) . "</td>";
								echo "<td bgColor='#E4EAEB' align = right>$" . number_format($quota_mtd, 0) . "</td>";
								echo "<td bgColor='#E4EAEB' align ='right'><font color='" . $color . "'>$" . number_format($actual_rev_mtd, 0) . "</font></td>";
								echo "<td bgColor='#E4EAEB' align = right><font color='" . $color . "'>" . number_format($actual_rev_mtd * 100 / $quota_mtd, 2) . "%</font></td>";
								echo "</tr>";
							}
							echo "</table><br><br>";

							//For Quarter
							$current_qtr = ceil(date('n', time()) / 3);
							?>
        <table cellSpacing="1" cellPadding="1" border="0" width="500">
            <tr>
                <td bgColor='#ABC5DF' colspan="5" align="center"><strong>Quarter wise Details</strong></td>
            </tr>
            <tr>
                <td bgColor='#ABC5DF' align="center"><strong>Quarter</strong></td>
                <td bgColor='#ABC5DF' align="center"><strong>Quota</strong></td>
                <td bgColor='#ABC5DF' align="center"><strong>Quota to Date</strong></td>
                <td bgColor='#ABC5DF' align="center"><strong>Actual To Date</strong></td>
                <td bgColor='#ABC5DF' align="center"><strong>Percentage</strong></td>
            </tr>

            <?php
								for ($qtr = 1; $qtr <= $current_qtr; $qtr++) {
									$month_quota = 0;
									if ($qtr == 1) {
										db();
										$result_q = db_query("Select quota_month, quota from employee_quota where emp_id = " . $loop_emp_id . " and quota_year = " . date("Y") . " and quota_month in(1,2,3) order by quota_month");

										$date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));

										//$sqlmtd = "SELECT SUM(inv_amount) AS invamt FROM loop_transaction_buyer WHERE po_employee LIKE '" . $rowemp["initials"];  
										//$sqlmtd .= "' and loop_transaction_buyer.ignore < 1 AND inv_amount > 0 and STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . date("Y-01-01") . "' AND '" . date("Y-03-31") . "'";

										$sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end)  BETWEEN '" . date("Y-01-01") . "' AND '" . date("Y-03-31") . " 23:59:59'";
									}
									if ($qtr == 2) {
										db();
										$result_q = db_query("Select quota_month, quota from employee_quota where emp_id = " . $loop_emp_id . " and quota_year = " . date("Y") . " and quota_month in(4,5,6) order by quota_month");

										$date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));

										//$sqlmtd = "SELECT SUM(inv_amount) AS invamt FROM loop_transaction_buyer WHERE po_employee LIKE '" . $rowemp["initials"];  
										//$sqlmtd .= "' and loop_transaction_buyer.ignore < 1 AND inv_amount > 0 and STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . date("Y-04-01") . "' AND '" . date("Y-06-30") . "'";
										$sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end)  BETWEEN '" . date("Y-04-01") . "' AND '" . date("Y-06-30") . " 23:59:59'";
									}
									if ($qtr == 3) {
										db();
										$result_q = db_query("Select quota_month, quota from employee_quota where emp_id = " . $loop_emp_id . " and quota_year = " . date("Y") . " and quota_month in(7,8,9) order by quota_month");

										$date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));

										//$sqlmtd = "SELECT SUM(inv_amount) AS invamt FROM loop_transaction_buyer WHERE po_employee LIKE '" . $rowemp["initials"];  
										//$sqlmtd .= "' and loop_transaction_buyer.ignore < 1 AND inv_amount > 0 and STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . date("Y-07-01") . "' AND '" . date("Y-09-30") . "'";
										$sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end)  BETWEEN '" . date("Y-07-01") . "' AND '" . date("Y-09-30") . " 23:59:59'";
									}
									if ($qtr == 4) {
										db();
										$result_q = db_query("Select quota_month, quota from employee_quota where emp_id = " . $loop_emp_id . " and quota_year = " . date("Y") . " and quota_month in(10,11,12) order by quota_month");

										$date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));

										//$sqlmtd = "SELECT SUM(inv_amount) AS invamt FROM loop_transaction_buyer WHERE po_employee LIKE '" . $rowemp["initials"];  
										//$sqlmtd .= "' and loop_transaction_buyer.ignore < 1 AND inv_amount > 0 and STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . date("Y-10-01") . "' AND '" . date("Y-12-31") . "'";
										$sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . date("Y-10-01") . "' AND '" . date("Y-12-31") . " 23:59:59'";
									}
									$dt_month_value_1 = date('m');
									$quota_mtd = 0;
									$donot_add = "";
									$days_in_month = 30;
									while ($rowemp_q = array_shift($result_q)) {
										$month_quota = $month_quota + $rowemp_q["quota"];

										$todays_dt = date('m/d/Y');
										$days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
										$days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
										if ($donot_add == "") {
											if ($rowemp_q["quota_month"] <= $dt_month_value_1) {
												if ($rowemp_q["quota_month"] == $dt_month_value_1) {
													$donot_add = "yes";
													$monthly_qtd_1 = ($days_today * $rowemp_q["quota"]) / $days_in_month;

													$quota_mtd = $quota_mtd + $monthly_qtd_1;
												} else {
													$quota_mtd = $quota_mtd + $rowemp_q["quota"];
												}
											}
										}
									}

									$actual_rev_mtd = 0;
									db();
									$resultmtd = db_query($sqlmtd);
									while ($summtd = array_shift($resultmtd)) {
										$finalpaid_amt = 0;
										db();
										$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]);
										while ($summtd_finalpmt = array_shift($result_finalpmt)) {
											$finalpaid_amt = $summtd_finalpmt["amt"];
										}

										$inv_amt_totake = 0;
										/*if ($finalpaid_amt > 0){
						if ($summtd["invsent_amt"] > 0){
							if ($finalpaid_amt < $summtd["invsent_amt"]){
								$inv_amt_totake= $summtd["invsent_amt"];
							}else{
								$inv_amt_totake= $finalpaid_amt;
							}
						}else{
							if ($finalpaid_amt < $summtd["inv_amount"]){
								$inv_amt_totake= $summtd["inv_amount"];
							}else{
								$inv_amt_totake= $finalpaid_amt;
							}
						}
					}
					if ($inv_amt_totake == 0 && $summtd["inv_amount"] > 0){
						if ($summtd["invsent_amt"] < $summtd["inv_amount"]){
							$inv_amt_totake= $summtd["inv_amount"];
						}else{
							$inv_amt_totake= $summtd["invsent_amt"];
						}				
					}
					if ($inv_amt_totake == 0 && $summtd["invsent_amt"] > 0){
						$inv_amt_totake= $summtd["invsent_amt"];
					}		*/
										if ($finalpaid_amt > 0) {
											$inv_amt_totake = $finalpaid_amt;
										}
										if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
											$inv_amt_totake = $summtd["invsent_amt"];
										}
										if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
											$inv_amt_totake = $summtd["inv_amount"];
										}

										$actual_rev_mtd = $actual_rev_mtd + $inv_amt_totake;
									}

									if ($actual_rev_mtd >= $quota_mtd) {
										$color = "green";
									} elseif ($actual_rev_mtd < $quota_mtd) {
										$color = "red";
									} else {
										$color = "black";
									};

									echo "<tr><td bgColor='#E4EAEB' align ='left'>Quarter " . $qtr . "</td>";
									echo "<td bgColor='#E4EAEB' align = 'right'>$" . number_format($month_quota, 0) . "</td>";
									echo "<td bgColor='#E4EAEB' align = right>$" . number_format($quota_mtd, 0) . "</td>";
									echo "<td bgColor='#E4EAEB' align ='right'><font color='" . $color . "'>$" . number_format($actual_rev_mtd, 0) . "</font></td>";
									echo "<td bgColor='#E4EAEB' align = right><font color='" . $color . "'>" . number_format($actual_rev_mtd * 100 / $quota_mtd, 2) . "%</font></td>";
									echo "</tr>";
								}
								echo "</table><br><br>";

								//For Yearly
								$month_quota = 0;
								$quota_mtd = 0;
								$donot_add = "";
								$days_in_month = 0;
								$dt_month_value_1 = date('m');
								db();
								$result_q = db_query("Select quota_month, quota, deal_quota from employee_quota where emp_id = " . $loop_emp_id . " and quota_year = " . date("Y") . " order by quota_month");
								while ($rowemp_q = array_shift($result_q)) {
									$month_quota = $month_quota + $rowemp_q["quota"];

									$todays_dt = date('m/d/Y');
									$days_today = 1 + (int)dateDiff($todays_dt, date('Y-m-01'));
									$days_in_month = 1 + (int)dateDiff(date('Y-m-t'), date('Y-m-01'));

									if ($donot_add == "") {
										if ($rowemp_q["quota_month"] <= $dt_month_value_1) {
											if ($rowemp_q["quota_month"] == $dt_month_value_1) {
												$donot_add = "yes";
												$monthly_qtd_1 = ($days_today * $rowemp_q["quota"]) / $days_in_month;

												$quota_mtd = $quota_mtd + $monthly_qtd_1;
											} else {
												$quota_mtd = $quota_mtd + $rowemp_q["quota"];
											}
										}
									}
								}
								$date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));

								//$sqlmtd = "SELECT SUM(inv_amount) AS invamt FROM loop_transaction_buyer WHERE po_employee LIKE '" . $rowemp["initials"];  
								//$sqlmtd .= "' and loop_transaction_buyer.ignore < 1 AND inv_amount > 0 and STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . date("Y-01-01") . "' AND '" . date("Y-12-31") . "'";

								$sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . date("Y-01-01") . "' AND '" . date("Y-12-31") . " 23:59:59'";
								db();
								$resultmtd = db_query($sqlmtd);
								$actual_rev_mtd = 0;
								while ($summtd = array_shift($resultmtd)) {
									$finalpaid_amt = 0;
									db();
									$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]);
									while ($summtd_finalpmt = array_shift($result_finalpmt)) {
										$finalpaid_amt = $summtd_finalpmt["amt"];
									}

									$inv_amt_totake = 0;
									/*if ($finalpaid_amt > 0){
					if ($summtd["invsent_amt"] > 0){
						if ($finalpaid_amt < $summtd["invsent_amt"]){
							$inv_amt_totake= $summtd["invsent_amt"];
						}else{
							$inv_amt_totake= $finalpaid_amt;
						}
					}else{
						if ($finalpaid_amt < $summtd["inv_amount"]){
							$inv_amt_totake= $summtd["inv_amount"];
						}else{
							$inv_amt_totake= $finalpaid_amt;
						}
					}
				}
				if ($inv_amt_totake == 0 && $summtd["inv_amount"] > 0){
					if ($summtd["invsent_amt"] < $summtd["inv_amount"]){
						$inv_amt_totake= $summtd["inv_amount"];
					}else{
						$inv_amt_totake= $summtd["invsent_amt"];
					}				
				}
				if ($inv_amt_totake == 0 && $summtd["invsent_amt"] > 0){
					$inv_amt_totake= $summtd["invsent_amt"];
				}	*/

									if ($finalpaid_amt > 0) {
										$inv_amt_totake = $finalpaid_amt;
									}
									if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
										$inv_amt_totake = $summtd["invsent_amt"];
									}
									if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
										$inv_amt_totake = $summtd["inv_amount"];
									}

									$actual_rev_mtd = $actual_rev_mtd + $inv_amt_totake;
								}

								//$todays_dt=date('m/d/Y');
								//$days_today = dateDiff($todays_dt,$date_qtr);
								//$quota_mtd = ($days_today*$month_quota)/365;

								if ($actual_rev_mtd >= $quota_mtd) {
									$color = "green";
								} elseif ($actual_rev_mtd < $quota_mtd) {
									$color = "red";
								} else {
									$color = "black";
								};

								$actual_last_mtd = 0;
								$lastyr = date("Y") - 1;
								$sqlmtd = "SELECT SUM(inv_amount) AS invamt FROM loop_transaction_buyer WHERE po_employee LIKE '" . $rowemp["initials"];
								$sqlmtd .= "' and loop_transaction_buyer.ignore < 1 AND inv_amount > 0 and STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . date($lastyr . "-01-01") . "' AND '" . date($lastyr . "-12-31") . "'";
								db();
								$resultmtd = db_query($sqlmtd);
								while ($row_mtd = array_shift($resultmtd)) {
									$actual_last_mtd = $row_mtd["invamt"];
								}

								$last_month_quota = 0;
								$sql_lastqtr_quota = "Select sum(quota) as quota from employee_quota where emp_id = " . $loop_emp_id . " and quota_year = " . $lastyr;
								db();
								$result_q = db_query($sql_lastqtr_quota);
								while ($rowemp_q = array_shift($result_q)) {
									$last_month_quota = $rowemp_q["quota"];
								}
								if ($actual_last_mtd >= $last_month_quota) {
									$color_last = "green";
								} elseif ($actual_last_mtd < $last_month_quota) {
									$color_last = "red";
								} else {
									$color_last = "black";
								};

								?>
            <table cellSpacing="1" cellPadding="1" border="0" width="500">
                <tr>
                    <td bgColor='#ABC5DF' colspan="5" align="center"><strong>Year <?php echo Date('Y'); ?></strong></td>
                </tr>
                <tr>
                    <td bgColor='#ABC5DF' align="center"><strong>Year</strong></td>
                    <td bgColor='#ABC5DF' align="center"><strong>Quota</strong></td>
                    <td bgColor='#ABC5DF' align="center"><strong>Quota to Date</strong></td>
                    <td bgColor='#ABC5DF' align="center"><strong>Actual To Date</strong></td>
                    <td bgColor='#ABC5DF' align="center"><strong>Percentage</strong></td>
                </tr>
                <?php
							echo "<tr><td bgColor='#E4EAEB' align ='left'>" . Date('Y') . "</td>";
							echo "<td bgColor='#E4EAEB' align = 'right'>$" . number_format($month_quota, 0) . "</td>";
							echo "<td bgColor='#E4EAEB' align = right>$" . number_format($quota_mtd, 0) . "</td>";
							echo "<td bgColor='#E4EAEB' align ='right'><font color='" . $color . "'>$" . number_format($actual_rev_mtd, 0) . "</font></td>";
							echo "<td bgColor='#E4EAEB' align = right><font color='" . $color . "'>" . number_format($actual_rev_mtd * 100 / $quota_mtd, 2) . "%</font></td>";
							echo "</tr>";
							echo "</table>";
						}
					}

					tep_db_close();
							?>

</body>

</html>