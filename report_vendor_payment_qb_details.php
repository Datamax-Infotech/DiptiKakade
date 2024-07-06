<?php

session_start();

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

//set_time_limit(0);	
//ini_set('memory_limit', '-1');

?>

<html>

<head>
    <title>Vendor Payment QB Details Report</title>
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


function set_emp_start_dt() {
    var seloption = document.getElementById('eid');
    var selectedindexforvalue = seloption.options[seloption.selectedIndex];
    var date3 = selectedindexforvalue.getAttribute('data-date');
    document.getElementById('date_from').value = date3;
}

function loadmainpg() {
    var date_from = document.getElementById('date_from').value;
    var date_to = document.getElementById('date_to').value;
    var dformat1 = "yyyy-MM-dd";
    var dformat2 = "yyyy-MM-dd";

    if (date_from != "" && date_to != "") {
        document.reportvendorpayment.submit();
        return true;
    }

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

.txtstyle,
.txtstyle_color {
    font-family: arial;
    font-size: 13;
    font-weight: 700;
    height: 16px;
    background: #ABC5DF;
    text-align: center;
}

.center {
    text-align: center;
}

.left {
    text-align: left;
}
</style>

<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
    rel="stylesheet">

<body>
    <?php require_once("inc/header.php"); ?>
    <br>
    <div class="outer-container">
        <div class="container">
            <div class="dashboard_heading" style="float: left;">
                <div style="float: left;">
                    Vendor Payment QB Details Report

                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                        <span class="tooltiptext">This report shows vendor payment QB details.</span>
                    </div><br>
                </div>
            </div>

            <form method="POST" name="reportvendorpayment" id="reportvendorpayment"
                action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <table>
                    <tr>
                        <td style="white-space: nowrap;">
                            <div id="showcal">
                                Date from:
                                <input type="text" name="date_from" id="date_from" size="8"
                                    value="<?php echo isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : date("Y-m-01"); ?>">
                                <a href="#"
                                    onclick="cal2xx.select(document.reportvendorpayment.date_from,'dtanchor2xx','yyyy-MM-dd'); return false;"
                                    name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
                                <div ID="listdiv"
                                    STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                </div>
                                &emsp;To:
                                <input type="text" name="date_to" id="date_to" size="8"
                                    value="<?php echo isset($_REQUEST['date_to']) ? $_REQUEST['date_to'] : date("Y-m-d"); ?>">
                                <a href="#"
                                    onclick="cal2xx.select(document.reportvendorpayment.date_to,'dtanchor3xx','yyyy-MM-dd'); return false;"
                                    name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
                                <div ID="listdiv"
                                    STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                </div>
                            </div>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QB Details:
                            <select name="qb_dd" id="qb_dd">
                                <option value="">All</option>
                                <option value="1" <?php if ($_POST["qb_dd"] == "1") {
                                                        echo "selected";
                                                    } else {
                                                    } ?>>Qb details not updated</option>
                                <option value="2" <?php if ($_POST["qb_dd"] == "2") {
                                                        echo "selected";
                                                    } else {
                                                    } ?>>Qb details updated</option>
                            </select>
                        </td>
                        <td>
                            <input type="button" value="Run Report" onClick="javascript: return loadmainpg()">
                            <input type="hidden" id="reprun" name="reprun" value="yes">
                        </td>

                    </tr>
                </table>
            </form>

            <br>
            <div class="content">
                <?php

                if (isset($_POST["reprun"]) && $_POST["reprun"] == "yes") {

                    $start_Dt = $_POST["date_from"];
                    $end_Dt = $_POST["date_to"];
                    $qb_dd = $_POST["qb_dd"];

                    $sstr = "";
                    /*if ($qb_dd == ""){
		$sstr = "";
	}
	elseif($qb_dd == "1"){
		$sstr = " and loop_transaction_buyer_payments.qb_inv_updated_by='' ";
	}
	elseif($qb_dd == "2"){
		$sstr = " and loop_transaction_buyer_payments.qb_inv_updated_by<>'' ";
	}*/

                    $lisoftrans = "<table cellSpacing='1' cellPadding='3' border='0' style='font-size:12px;'>";
                    $lisoftrans .= "<tr><td class='txtstyle_color' width='3%'>Sr. No.</td><td class='txtstyle_color' width='3%'>Loop ID</td><td class='txtstyle_color' width='15%'>Company Name</td>
	<td class='txtstyle_color' width='6%'>Vendor Payment Type</td><td class='txtstyle_color' width='6%'>Expected Cost</td>
	<td class='txtstyle_color' width='6%'>Invoiced Costs</td><td class='txtstyle_color' width='2%'>Confirm By /<br>Confirm on</td>
	<td class='txtstyle_color' width='6%'>Payment Company</td>
	<td class='txtstyle_color' width='2%'>Employee</td><td class='txtstyle_color' width='6%'>Date</td><td class='txtstyle_color' width='20%'>Notes</td><td class='txtstyle_color' width='8%'>QB Invoice #</td><td class='txtstyle_color' width='8%'>QB Invoice Date</td><td class='txtstyle_color' width='25%'>QB Update Details</td></tr>";

                    echo $lisoftrans;

                    $lisoftrans = "";
                    $dt_year_value = date('Y', strtotime($start_Dt));
                    $dt_month_value = date('m', strtotime($start_Dt));
                    $current_year_value = date('Y');
                    $slno = 1;
                    $qry = db_query("Select loop_transaction_buyer_payments.*, loop_warehouse.b2bid, loop_warehouse.id as warehouse_id , loop_vendor_type.typename, 
	files_companies.name AS vendor_comp, loop_employees.initials, loop_warehouse.warehouse_name as warehouse_name  FROM loop_transaction_buyer_payments 
	inner JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id
	left JOIN loop_employees ON loop_transaction_buyer_payments.employee_id=loop_employees.id
	inner join loop_transaction_buyer on loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
	inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id 
	inner JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id
	where loop_transaction_buyer.ignore = 0 $sstr and loop_transaction_buyer_payments.entry_date_sys_gen between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' order by loop_transaction_buyer_payments.transaction_buyer_id, loop_transaction_buyer_payments.typeid");


                    $rec_display = 1;
                    while ($row_rs_tmprs = array_shift($qry)) {

                        $comp_name = get_nickname_val($row_rs_tmprs["warehouse_name"], $row_rs_tmprs["b2bid"]);
                        if ($qb_dd == "1") {

                            if ($row_rs_tmprs["qb_inv_updated_by"] != "") {
                                $rec_display = 0;
                            } else {
                                $rec_display = 1;
                            }
                        }
                        if ($qb_dd == "2") {
                            if ($row_rs_tmprs["qb_inv_updated_by"] != "") {
                                $rec_display = 1;
                            } else {
                                $rec_display = 0;
                            }
                        }
                        //
                        if ($rec_display == 1) {
                            $lisoftrans .= "<tr>
			<td class='center' bgColor='#E4EAEB'>" . $slno++ . "</td>
			<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $row_rs_tmprs["b2bid"] . "&show=transactions&warehouse_id=" . $row_rs_tmprs["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row_rs_tmprs["warehouse_id"] . "&rec_id=" . $row_rs_tmprs["transaction_buyer_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["transaction_buyer_id"] . "</a></td>
			<td bgColor='#E4EAEB'>" . $comp_name . "</td>
			<td class='center' bgColor='#E4EAEB'>" . $row_rs_tmprs["typename"] . "</td>
			<td bgColor='#E4EAEB' align='right'>$" . number_format($row_rs_tmprs["estimated_cost"], 2) . "</td>
			<td bgColor='#E4EAEB' align='right'>$" . number_format($row_rs_tmprs["amount"], 2) . "</td>
			<td class='center' bgColor='#E4EAEB'>" . $row_rs_tmprs["confirm_by"] . "<br>" . $row_rs_tmprs["confirm_on"] . "</td>

			<td class='center' bgColor='#E4EAEB'>" . $row_rs_tmprs["vendor_comp"] . "</td>
			<td class='center' bgColor='#E4EAEB'>" . $row_rs_tmprs["initials"] . "</td>

			<td class='center' bgColor='#E4EAEB'>" . $row_rs_tmprs["date"] . "</td>
			<td class='left' bgColor='#E4EAEB'>" . $row_rs_tmprs["notes"] . "</td>
			<td class='left' bgColor='#E4EAEB'>" . $row_rs_tmprs["qb_inv_no"] . "</td>
			<td class='left' bgColor='#E4EAEB'>" . $row_rs_tmprs["qb_inv_date"] . "</td>
			<td class='left' bgColor='#E4EAEB'>";
                            if ($row_rs_tmprs["qb_inv_updated_by"] != "") {
                                $lisoftrans .= "Updated by <br>" .  $row_rs_tmprs["qb_inv_updated_by"] . " on " . $row_rs_tmprs["qb_inv_updated_on"] . "";
                            } else {
                                $lisoftrans .= "<span style='color:red;'>QB Not Updated</span>";
                            }

                            $lisoftrans .= "</td>
			</tr>";
                        }
                    }
                    echo $lisoftrans;

                    echo "</table>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>