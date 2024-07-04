<?php 

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">


<html>

<head>

    <title>Order Source Report</title>
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">

    <style type="text/css">
    .genstyle {
        font-size: 12;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
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
    </style>
</head>


<body>

    <?php include("inc/header.php"); ?>

    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                Order Source Report

                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">Order Source Report All Sources By Date</span>
                </div><br>
            </div>
        </div>

        <form name="report_order_source" action="order_source_report.php" method="GET">

            <input type="hidden" name="frmsubmit" value="">

            <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
            <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>

            <script LANGUAGE="JavaScript">
            document.write(getCalendarStyles());
            </script>

            <script LANGUAGE="JavaScript">
            function checkvalidation() {

                if (document.report_order_source.start_date.value == "") {
                    alert("Please select the Start date.");
                    return false;
                }

                if (document.report_order_source.end_date.value == "") {
                    alert("Please select the End date.");
                    return false;
                }

                if (document.report_order_source.start_date.value > document.report_order_source.end_date.value) {
                    alert("Start date is greater then End date, please check.");
                    return false;
                }

                /*if (document.report_order_source.lstbuysell.value == "S")
                {
                	if ((document.report_order_source.inpqty.value != "") || (document.report_order_source.inpboxwall.value != ""))
                	{
                		alert("For Sell 'Quantity' and 'Box Wall' paramter not applied.");
                		return false;
                	}
                }*/

                document.report_order_source.frmsubmit.value = "yes";
            }

            var cal1xx = new CalendarPopup("listdiv");

            cal1xx.showNavigationDropdowns();

            var cal2xx = new CalendarPopup("listdiv");

            cal2xx.showNavigationDropdowns();

            function updsortfld(fldname) {
                document.report_order_source.sortfld.value = fldname;
                document.report_order_source.frmsubmit.value = "yes";
                document.report_order_source.submit();
            }

            function updsortfldloop(fldname) {
                document.report_order_source.loopsortfld.value = fldname;
                document.report_order_source.frmsubmit.value = "yes";
                document.report_order_source.submit();
            }
            </script>

            <?php 

$start_date = isset($_GET["start_date"])?strtotime($_GET["start_date"]):strtotime(date('Y-m-d'));
$end_date = isset($_GET["end_date"])?strtotime($_GET["end_date"]):strtotime(date('Y-m-d'));
?>

            <input type="hidden" id="sortfld" name="sortfld" value="<?php echo $_GET["sortfld"] ?>">
            <input type="hidden" id="loopsortfld" name="loopsortfld" value="<?php echo $_GET["loopsortfld"] ?>">


            <br /><br />
            <font size="3">Select the parameters given below</font><br />

            <table cellSpacing="0" cellPadding="0" border="0" class="genstyle">
                <tr>
                    <td width="130px">Transcation date from: </td>

                    <td width="130px">
                        <input type="text" name="start_date" id="start_date" size="11"
                            value="<?php echo (isset($_GET["start_date"]) && $_GET["start_date"] != "")?date('m/d/Y', $start_date):""?>">
                        <a href="#"
                            onclick="cal1xx.select(document.report_order_source.start_date,'anchor1xx','MM/dd/yyyy'); return false;"
                            name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
                    </td>

                    <td width="20px">To:</td>

                    <td width="100px">
                        <input type="text" name="end_date" id="end_date" size="11"
                            value="<?php echo (isset($_GET["end_date"]) && $_GET["start_date"] != "")?date('m/d/Y', $end_date):""?>">
                        <a href="#"
                            onclick="cal1xx.select(document.report_order_source.end_date,'anchor2xx','MM/dd/yyyy'); return false;"
                            name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
                    </td>
                    <td width="20px"><input type="submit" value="Search" class="genstyle" onclick="checkvalidation()">
                    </td>
                </tr>

                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
            </table>


        </form>

        <div ID="listdiv"
            STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

        <?php 

if ($_GET["frmsubmit"] == 'yes') {

$start_date = date('Y-m-d', $start_date);
$end_date = date('Y-m-d', $end_date + 86400);

?>

        <br /><br />
        <table cellSpacing="1" cellPadding="1" width="800" border="0" class="style3">

            <tr vAlign="center" class="style7">
                <td colspan="9" align="center">
                    <font size="2"><b>List the Customer who selected "Other" when placed the order</b></font>
                </td>
            </tr>


            <tr vAlign="center" class="style7">
                <td width="200px">Customer name</td>
                <td>Address</td>
                <td>Address2</td>
                <td>City</td>
                <td>State</td>
                <td>Zip code</td>
                <td>Email</td>
                <td>Date</td>
                <td>Reason Given in "Other"</td>
            </tr>

            <?php 

	$query = "Select customers_name, customers_company, customers_street_address, customers_street_address2,  ";
	$query.= " customers_suburb, customers_city, customers_postcode, customers_state, customers_email_address, date_purchased, how_to_hear_about from orders ";
	$query.= " where (date_purchased >='$start_date'";
	$query.= " AND date_purchased <='$end_date') and how_to_hear_about like '%Other%' ";
	$query = $query . " order by date_purchased ";
	
//echo $query;

db();
$res = db_query($query);
$num_rows = tep_db_num_rows($res);
?>
            <tr vAlign="center" class="style7">
                <td colspan="9"><b>Number of rows <?php echo $num_rows ?></b></td>
            </tr>

            <?php 

while($row = array_shift($res))
{
 ?>

            <tr vAlign="center">
                <td bgColor="#e4e4e4" width="200px" style="height: 22px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $row["customers_name"]; ?>
                    </font>
                </td>
                <td bgColor="#e4e4e4" width="200px" style="height: 22px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $row["customers_street_address"]; ?>
                    </font>
                </td>
                <td bgColor="#e4e4e4" width="100px" style="height: 22px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $row["customers_street_address2"]; ?>
                    </font>
                </td>
                <td bgColor="#e4e4e4" width="100px" style="height: 22px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $row["customers_city"]; ?>
                    </font>
                </td>
                <td bgColor="#e4e4e4" width="50px" style="height: 22px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $row["customers_state"]; ?>
                    </font>
                </td>
                <td bgColor="#e4e4e4" width="70px" style="height: 22px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $row["customers_postcode"]; ?>
                    </font>
                </td>
                <td bgColor="#e4e4e4" width="100px" style="height: 22px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $row["customers_email_address"]; ?>
                    </font>
                </td>
                <td bgColor="#e4e4e4" width="100px" style="height: 22px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $row["date_purchased"]; ?>
                    </font>
                </td>

                <td bgColor="#e4e4e4" width="200px" style="height: 22px;">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php if ($row["how_to_hear_about"] != "") 
				{
					$tmppos_1 = strpos($row["how_to_hear_about"], ":");
					if ($tmppos_1 != false)
					{ 	
						echo substr($row["how_to_hear_about"], strpos($row["how_to_hear_about"],':')+1); 
					}
				}
			?>
                    </font>
                </td>

            </tr>


            <?php 
}

?>


        </table>

        <?php 
}

?>
    </div>

</body>

</html>