<?php 

require("../mainfunctions/database.php"); 
require("../mainfunctions/general-functions.php");
$return_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "milwaukeeywarehouse_14159265358979.php";


// error_reporting(E_ALL);
// ini_set("display_errors", "1");

?>
<!DOCTYPE HTML>

<html>

<head>
    <title>General Mills(GMI) - Dashboard</title>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();
    </script>
    <script language="JavaScript">
    function FormCheck() {

        if (document.BOLForm.trailer_no.value == "" |
            document.BOLForm.fullname.value == "") {
            alert("Please Complete All Field.\n Need help? Call 1-888-BOXES-88");
            return false;
        }
    }

    function chk_field(e) {
        var ele = document.getElementById("check_" + e).checked;
        if (ele == true) {
            document.getElementById("count_" + e).disabled = false;
            document.getElementById("weight_" + e).disabled = false;
        } else {
            document.getElementById("count_" + e).disabled = true;
            document.getElementById("weight_" + e).disabled = true;
        }
    }
    </SCRIPT>
    <script type="text/javascript">
    function update_cart() {
        var x
        var total = 0
        var order_total
        for (x = 1; x <= 5; x++) {
            item_total = document.getElementById("weight_" + x)
            total = total + item_total.value * 1
        }
        order_total = document.getElementById("order_total")
        document.getElementById("order_total").value = total.toFixed(0)
        var totalcount = 0
        var count_total
        for (x = 1; x <= 5; x++) {
            count_total = document.getElementById("count_" + x)
            totalcount = totalcount + count_total.value * 1
        }
        count_total = document.getElementById("count_total")
        document.getElementById("count_total").value = totalcount.toFixed(0)
    }
    </script>
    <style>
    .style1 {
        font-size: small;
        font-weight: 700;
        color: #333333;
        background-color: #FFCC66;
        text-align: center;
    }

    .style2 {
        font-weight: 500;
        font-size: x-small;
        color: #333333;
        background-color: #99FF99;
        text-align: center;
    }

    .style3 {
        font-size: x-small;
        color: #333333;
        background-color: #E4E4E4;
    }

    .ftbold {
        font-weight: 900;
    }

    .txtcenter {
        text-align: center;
    }

    .txtright {
        text-align: right;
    }
    </style>
</head>

<body>
    <?php include("inc/header.php"); ?>
    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">GMI Inbound Trailer Report</div>
            &nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                <span class="tooltiptext">This report allows the user to search the sort report database for all
                    inventory received over a period of time, ........</span>
            </div>
            <div style="height: 13px;">&nbsp;</div>
        </div>
        <div id="light" class="white_content"></div>
        <div id="fade" class="black_overlay"></div>
        <?php
		//echo $return_url;
	?>
        <form method="post" name="BOLForm" action="hannibal_gmi_inbound_trailer_save.php">
            <input type=hidden value="" name="carrier" id="carrier">
            <input type=hidden value="<?php echo $return_url;?>" name="return_url">
            <input type=hidden value="" id="dock" name="dock">

            <table border="0">
                <tr>
                    <td colSpan="4" class="style1">
                        <b>Request a Trailer WITH a Bill of Lading (BOL)</b>
                    </td>
                </tr>
                <tr>
                    <td class="style2" colspan="4">Trailer Information</td>
                </tr>
                <tr>
                    <td class="style3 txtright" colspan="2">
                        <labal>Warehouse:</labal>
                    </td>
                    <td class="style3" colspan="2">
                        <?php 
					
					$sqlw = "SELECT b2bid, id , company_name FROM loop_warehouse WHERE rec_type LIKE 'Manufacturer' AND ACTIVE = 1 ORDER BY company_name ASC " ;
                    db();
					$sql_resw = db_query($sqlw);
					while ($roww = array_shift($sql_resw)) {
						$warehouse_name = $roww["company_name"];
						
						$com_name = get_nickname_val($warehouse_name, $roww["b2bid"]);
						
						$shipAddress = "";
						$com_city = "";
						$com_state = "";
						$sqlw1 = "SELECT shipAddress,state,city FROM companyInfo WHERE ID = '" . $roww["b2bid"] . "'";
                        db_b2b();
						$sql_resw1 = db_query($sqlw1);
						while ($roww1 = array_shift($sql_resw1)) {
							$shipAddress = $roww1["shipAddress"];
							$com_city = $roww1["city"];
							$com_state = $roww1["state"];
						}
								
						$MGArray[] = array('loop_id' => $roww["id"], 'b2b_id' => $roww["b2bid"], 'company_name' => $com_name, 'city' => $com_city, 'state' => $com_state, 'shipAddress' => $shipAddress);
					}
                    
                    // $temp1 = array();
					foreach($MGArray as $arrtmp){
						if(strpos($arrtmp['company_name'],"GMI") !== false || strpos($arrtmp['company_name'],"General Mills") !== false || $arrtmp['b2b_id'] == 114965){
							$temp1[] = $arrtmp;
						}
					}
					
					$MGArray = $temp1;
					
					$MGArraysort_I = array();
					foreach ($MGArray as $MGArraytmp) {
						$MGArraysort_I[] = $MGArraytmp['company_name'];
					}
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 

					?>

                        <select name="warehouse_id" name="warehouse_id" style="width:400px;">
                            <option value="<?php echo $_REQUEST["warehouse_id"];?>">Select One</option>
                            <?php 
					  foreach ($MGArray as $MGArraytmp2) {
						if (trim($MGArraytmp2["company_name"]) != ""){
						
							$sel_text = "";
							if ($MGArraytmp2["loop_id"] == $_REQUEST["warehouse_id"]){
								$sel_text = " selected ";
							}	
							echo "<option value='" . $MGArraytmp2["loop_id"] . "' $sel_text>";
						
					?>
                            <?php //echo $MGArraytmp2["company_name"] . " (Loop ID: " . $MGArraytmp2["loop_id"] . " B2B ID:" . $MGArraytmp2["b2b_id"] . ")";
							// echo $MGArraytmp2["company_name"] . " (" . $MGArraytmp2["shipAddress"] . ")";
							echo $MGArraytmp2["company_name"] . " - ".$MGArraytmp2["city"].", ".$MGArraytmp2["state"]." (" . $MGArraytmp2["shipAddress"] . ")";
						?>
                            </option>
                            <?php }} ?>
                        </select>
                        <?php  ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="style3 txtright">
                        <labal>Trailer Number:</labal>
                    </td>
                    <td colspan="2" class="style3">
                        <input name="trailer_no" id="trailer_no" value="" style="width:180px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="style3 txtright">
                        <labal>Dock:</labal>
                    </td>
                    <td colspan="2" class="style3">
                        <select name="destination_id" style="width:180px;">
                            <option value="8">OCC Dock</option>
                            <option value="9">Gaylord Dock</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="style3 txtright">
                        <labal>GMI_Order #:</labal>
                    </td>
                    <td colspan="2" class="style3">
                        <input name="GMI_Order" value="" style="width:180px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="style3 txtright">
                        <labal>GMI_Delivery #:</labal>
                    </td>
                    <td colspan="2" class="style3">
                        <input name="GMI_Delivery" value="" style="width:180px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="style3 txtright">
                        <labal>GMI_Shipment #:</labal>
                    </td>
                    <td colspan="2" class="style3">
                        <input name="GMI_Shipment" value="" style="width:180px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="style3 txtright">
                        <labal>Seal Number:</labal>
                    </td>
                    <td colspan="2" class="style3">
                        <input name="seal_no" value="" style="width:180px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="style3 txtright">
                        <labal>Your Name:</labal>
                    </td>
                    <td colspan="2" class="style3">
                        <input name="fullname" value="" style="width:180px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="style3 txtright">
                        <labal>Pickup Date:</labal>
                    </td>
                    <td colspan="2" class="style3">
                        <?php 
$pickup_date = isset($_REQUEST["pickup_date"])?strtotime($_REQUEST["pickup_date"]):strtotime(date('m/d/Y'));
?>
                        <input type="text" name="pickup_date" size="11"
                            value="<?php echo date('m/d/Y', $pickup_date);?>"> <a href="#"
                            onclick="cal2xx.select(document.BOLForm.pickup_date,'anchor0xx','MM/dd/yyyy'); return false;"
                            name="anchor0xx" id="anchor0xx"><img border="0" src="images/calendar.jpg"></a>
                    </td>
                </tr>
                <tr>
                    <td colSpan="4" class="style1">Item Information</td>
                </tr>
                <tr>
                    <td class="style2" colspan="4">Please check the items below if they are being
                        shipped and enter weights below if known.</td>
                </tr>

                <tr>
                    <td class="style3 txtcenter ftbold">
                        <labal>Check</labal>
                    </td>
                    <td class="style3 txtcenter ftbold">
                        <labal>Count</labal>
                    </td>
                    <td class="style3 txtcenter ftbold">
                        <labal>Weight</labal>
                    </td>
                    <td class="style3 txtcenter ftbold">
                        <labal>Item</labal>
                    </td>
                </tr>
                <tr>
                    <td class="style3">
                        <input name="check_1" id="check_1" type="checkbox" value="1" onclick="chk_field(1)">
                    </td>
                    <td class="style3">
                        <input name="count_1" size="10" id="count_1" onchange="update_cart()" disabled="true">
                    </td>
                    <td class="style3">
                        <input name="weight_1" size="10" id="weight_1" onchange="update_cart()" disabled="true">
                    </td>
                    <td class="style3">
                        Bales of OCC<input type="hidden" name="item_1" value="HPT-41 Totes">
                    </td>
                </tr>
                <tr>
                    <td class="style3">
                        <input name="check_2" id="check_2" type="checkbox" value="1" onclick="chk_field(2)">
                    </td>
                    <td class="style3">
                        <input name="count_2" size="10" value="" id="count_2" onchange="update_cart()" disabled="true">
                    </td>
                    <td class="style3">
                        <input name="weight_2" size="10" value="" id="weight_2" onchange="update_cart()"
                            disabled="true">
                    </td>
                    <td class="style3">
                        Gaylord Totes<input type="hidden" name="item_2" value="Gaylord Totes">
                    </td>
                </tr>
                <tr>
                    <td class="style3">
                        <input name="check_3" id="check_3" type="checkbox" value="1" onclick="chk_field(3)">
                    </td>
                    <td class="style3">
                        <input name="count_3" size="10" id="count_3" onchange="update_cart()" disabled="true">
                    </td>
                    <td class="style3">
                        <input name="weight_3" size="10" id="weight_3" onchange="update_cart()" disabled="true">
                    </td>
                    <td class="style3">
                        Bales of Plastic<input type="hidden" name="item_3" value="Bales of Plastic">
                    </td>
                </tr>
                <tr>
                    <td class="style3">
                        <input name="check_4" id="check_4" onclick="chk_field(4)" type="checkbox" value="1">
                    </td>
                    <td class="style3">
                        <input name="count_4" size="10" id="count_4" onchange="update_cart()" disabled="true">
                    </td>
                    <td class="style3">
                        <input name="weight_4" size="10" id="weight_4" onchange="update_cart()" disabled="true">
                    </td>
                    <td class="style3">
                        <input type="text" name="item_4" size="55"
                            value="Other: Check box &amp; replace text with item description">
                    </td>
                </tr>
                <tr>
                    <td class="style3">
                        <input name="check_5" id="check_5" onclick="chk_field(5)" type="checkbox" value="1">
                    </td>
                    <td class="style3">
                        <input name="count_5" size="10" id="count_5" value="" onchange="update_cart()" disabled="true">
                    </td>
                    <td class="style3">
                        <input name="weight_5" size="10" id="weight_5" value="" onchange="update_cart()"
                            disabled="true">
                    </td>
                    <td class="style3">
                        <input type="text" size="55" value="Other: Check box &amp; replace text with item description"
                            name="item_5">
                    </td>
                </tr>

                <tr>
                    <td class="style3">

                    </td>
                    <td class="style3">
                        <input name="count_total" id="count_total" size="10" value="">
                    </td>
                    <td class="style3">
                        <input name="order_total" id="order_total" size="10" value="">
                    </td>
                    <td class="style3 ftbold">
                        Total
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="style3 txtcenter">
                        <input type="submit" name="btntool" value="Generate Bill of Lading and Request Trailer"
                            onclick="FormCheck();" />
                    </td>
                </tr>
                <div ID="listdiv"
                    STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                </div>
            </table>
        </form>

    </div>
</body>

</html>