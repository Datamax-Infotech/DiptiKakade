<script LANGUAGE="JavaScript">
function display_file(val) {

    document.getElementById('fileview').innerHTML = "<embed src='" + val + "' width='500' height='600'>";

}

function display_file2(val) {

    document.getElementById('fileview2').innerHTML = "<embed src='" + val + "' width='500' height='600'>";

}

function display_file3(val) {

    document.getElementById('fileview3').innerHTML = "<embed src='" + val + "' width='500' height='600'>";

}

function display_file4(val) {

    document.getElementById('fileview4').innerHTML = "<embed src='" + val + "' width='500' height='600'>";

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



function updateb2bsurveyignore(bol_id, rec_id, ID, warehouse_id)

{

    document.getElementById("divbol_confirmshipmentreceipt").innerHTML =
        "<br><br>Loading .....<img src='images/wait_animated.gif' />";



    if (window.XMLHttpRequest)

    {

        xmlhttp = new XMLHttpRequest();

    } else

    {

        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    }

    xmlhttp.onreadystatechange = function()

    {

        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)

        {

            document.getElementById("divbol_confirmshipmentreceipt").innerHTML = xmlhttp.responseText;

            document.getElementById('light_details').style.display = 'none';

        }

    }



    xmlhttp.open("GET", "bol_confirmshipmentreceipt_ignoresurvey.php?bol_id=" + bol_id + "&rec_id=" + rec_id + "&ID=" +
        ID + "&warehouse_id=" + warehouse_id, true);

    xmlhttp.send();

}



function showconfirmdelivery()

{

    document.getElementById("light_details").innerHTML =
        "<a href='javascript:void(0)' onclick=document.getElementById('light_details').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
        document.getElementById('div_bol_confirmshipmentreceipt').innerHTML;

    document.getElementById('light_details').style.display = 'block';



    var selectobject;

    selectobject = document.getElementById("btnshowconfirmdelivery");



    var n_left = f_getPosition(selectobject, 'Left');

    var n_top = f_getPosition(selectobject, 'Top');



    document.getElementById('light_details').style.left = n_left + 10 + 'px';

    document.getElementById('light_details').style.top = n_top + 20 + 'px';

    document.getElementById('light_details').style.width = 600 + 'px';

}



function updatebolflgs(bol_id, rec_id, ID, warehouse_id)

{

    document.getElementById("divbol_confirmshipmentreceipt").innerHTML =
        "<br><br>Loading .....<img src='images/wait_animated.gif' />";



    if (window.XMLHttpRequest)

    {

        xmlhttp = new XMLHttpRequest();

    } else

    {

        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    }

    xmlhttp.onreadystatechange = function()

    {

        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)

        {

            document.getElementById("divbol_confirmshipmentreceipt").innerHTML = xmlhttp.responseText;

            document.getElementById('light_details').style.display = 'none';

        }

    }



    xmlhttp.open("GET", "bol_confirmshipmentreceipt_new.php?bol_id=" + bol_id + "&txtbookeddeliverycost=" + document
        .getElementById("txtbookeddeliverycost").value + "&txtbol_other_freight_cost=" + document.getElementById(
            "txtbol_other_freight_cost").value + "&rec_id=" + rec_id + "&ID=" + ID + "&warehouse_id=" +
        warehouse_id, true);

    xmlhttp.send();

}



function reminder_popup_send_cust_email(compid, rec_id, warehouse_id, rec_type)

{

    if (window.XMLHttpRequest)

    {

        xmlhttp = new XMLHttpRequest();

    } else

    {

        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    }

    xmlhttp.onreadystatechange = function()

    {

        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)

        {

            selectobject = document.getElementById("truckfellof_eml");

            n_left = f_getPosition(selectobject, 'Left');

            n_top = f_getPosition(selectobject, 'Top');



            document.getElementById("light_reminder").innerHTML = "";



            document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;

            document.getElementById('light_reminder').style.display = 'block';



            document.getElementById('light_reminder').style.left = n_left + 'px';

            document.getElementById('light_reminder').style.top = n_top + 20 + 'px';

            document.getElementById('light_reminder').style.width = 1100 + 'px';

        }

    }



    xmlhttp.open("POST", "truckfellof_send_cust_email.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
        warehouse_id + "&rec_type=" + rec_type, true);

    xmlhttp.send();

}



function btnsendeml_cust_truck_felloff()

{

    var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;



    tmp_element1 = document.getElementById("txtemailto").value;



    tmp_element2 = document.getElementById("email_reminder_sch_p2");



    tmp_element3 = document.getElementById("txtemailcc").value;



    tmp_element4 = document.getElementById("txtemailsubject").value;



    tmp_element5 = document.getElementById("hidden_reply_eml");



    if (tmp_element1.value == "")

    {

        alert("Please enter the To Email address.");

        return false;

    }



    if (tmp_element4.value == "")

    {

        alert("Please enter the Email Subject.");

        return false;

    }



    if (tmp_element3.value == "")

    {

        alert("Please enter the Cc Email address.");

        return false;

    }





    var inst = FCKeditorAPI.GetInstance("txtemailbody");

    var emailtext = inst.GetHTML();



    tmp_element5.value = emailtext;

    //alert(tmp_element5.value);

    document.getElementById("hidden_sendemail").value = "inemailmode";

    tmp_element2.submit();



}



/* -------------------------------------------------------------------------------

New function added for open popup window for mail as delivery of product done

------------------------------------------------------------------------------- */



function item_delivered_mail(compid, rec_id, warehouse_id, rec_type)

{

    if (window.XMLHttpRequest)

    {

        xmlhttp = new XMLHttpRequest();

    } else

    {

        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    }

    xmlhttp.onreadystatechange = function()

    {

        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)

        {

            selectobject = document.getElementById("item_delivered_mail_button");

            n_left = f_getPosition(selectobject, 'Left');

            n_top = f_getPosition(selectobject, 'Top');



            document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;

            document.getElementById('light_reminder').style.display = 'block';



            document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';

            document.getElementById('light_reminder').style.top = n_top - 40 + 'px';

            document.getElementById('light_reminder').style.width = 800 + 'px';

        }

    }



    xmlhttp.open("POST", "sendemail_b2bitemreceived.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
        warehouse_id + " &rec_type=" + rec_type, true);

    xmlhttp.send();

}



/***************************************************************************************************************/



function item_ignore_OrdDelvrd(compid, rec_id, action) {

    document.getElementById("divbol_confirmshipmentreceipt").innerHTML =
        "<br><br>Loading .....<img src='images/wait_animated.gif' />";

    if (window.XMLHttpRequest)

    {

        xmlhttp = new XMLHttpRequest();

    } else

    {

        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    }

    xmlhttp.onreadystatechange = function()

    {

        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)

        {

            if (xmlhttp.responseText == true) {

                location.reload();

            }

        }

    }



    xmlhttp.open("GET", "bol_item_ignore_OrdDelvrd.php?compid=" + compid + "&rec_id=" + rec_id + "&action=" + action,
        true);

    xmlhttp.send();

}



function view_orderissue(boximg)

{

    alert(boximg);

}



function send_confirm_delivery_email(compid, rec_id, warehouse_id)

{

    if (window.XMLHttpRequest)

    {

        xmlhttp = new XMLHttpRequest();

    } else

    {

        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    }

    xmlhttp.onreadystatechange = function()

    {

        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)

        {

            selectobject = document.getElementById("confirm_delivered_mail_button");

            n_left = f_getPosition(selectobject, 'Left');

            n_top = f_getPosition(selectobject, 'Top');



            document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;

            document.getElementById('light_reminder').style.display = 'block';



            document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';

            document.getElementById('light_reminder').style.top = n_top - 40 + 'px';

            document.getElementById('light_reminder').style.width = 800 + 'px';

        }

    }



    xmlhttp.open("POST", "bol_confirm_delivery_email_send.php?compid=" + compid + "&rec_id=" + rec_id +
        "&warehouse_id=" + warehouse_id, true);

    xmlhttp.send();

}



function order_issue_img_delete(order_img_id, rec_id, warehouse_id, compid)

{

    var rec_type = document.getElementById("rec_type").value;



    if (confirm("Do you want to delete selected picture?") == true) {

        if (window.XMLHttpRequest)

        {

            xmlhttp = new XMLHttpRequest();

        } else

        {

            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

        }

        xmlhttp.onreadystatechange = function()

        {

            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)

            {



                document.getElementById("orderissue_tbl").innerHTML = xmlhttp.responseText;



            }

        }



        xmlhttp.open("POST", "order_issue_imgs_delete.php?order_img_id=" + order_img_id + "&compid=" + compid +
            "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type + "&delete=yes", true);

        xmlhttp.send();

    }

}



function view_orderissue_img(boximg, imgid)

{



    selectobject = document.getElementById("orderissue" + imgid);

    n_left = f_getPosition(selectobject, 'Left');

    n_top = f_getPosition(selectobject, 'Top');

    //

    document.getElementById("light").innerHTML =
        "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

    document.getElementById('light').style.display = 'block';



    document.getElementById("light").innerHTML =
        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';>Close</a> <br><hr>" +
        "<img src='" + boximg + "' width='500px' height='500px'>";

    document.getElementById('light').style.left = (n_left + 200) + 'px';

    document.getElementById('light').style.top = n_top + 80 + 'px';

    document.getElementById('light').style.height = 550 + 'px';

    //

}
</script>

<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>

<script LANGUAGE="JavaScript">
document.write(getCalendarStyles());
</script>

<script LANGUAGE="JavaScript">
var cal3xx = new CalendarPopup("listdiv");

cal3xx.showNavigationDropdowns();
</script>

<style>
table.orderissue-style {

    border-collapse: collapse;

}



table.orderissue-style tr {

    border-top: 1px solid #FFF;

    border-bottom: 1px solid #FFF;

}

.black_overlay {

    display: none;

    position: absolute;

}

.white_content {

    display: none;

    position: absolute;

    padding: 5px;

    border: 2px solid black;

    background-color: white;

    overflow: auto;

    height: 600px;

    width: 850px;

    z-index: 1002;

    margin: 0px 0 0 0px;

    padding: 10px 10px 10px 10px;

    border-color: black;

    border-width: 2px;

    overflow: auto;

}
</style>

<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
</div>



<div id="light" class="white_content">

</div>

<div id="fade" class="black_overlay"></div>

<div id="light_reminder" class="white_content_reminder"></div>





<?php

$order_issue_val = "";
$order_issue_pictures_val = "";
$customerpickup_ucbdelivering_flg = "";
$virtual_inventory_trans_id = 0;
$proof_of_delivery = "";
$pod_done_by = "";
$pod_done_on = "";



db();
$getdata = db_query("Select virtual_inventory_trans_id, customerpickup_ucbdelivering_flg, order_issue, order_issue_pictures From loop_transaction_buyer where id = " . $_REQUEST["rec_id"]);

while ($getdata_row = array_shift($getdata)) {

	$customerpickup_ucbdelivering_flg 	= $getdata_row["customerpickup_ucbdelivering_flg"];

	$virtual_inventory_trans_id 		= $getdata_row["virtual_inventory_trans_id"];

	$order_issue_val = $getdata_row["order_issue"];

	$order_issue_pictures_val = $getdata_row["order_issue_pictures"];
}

?>



<!------------------------------ Start Truck Fell off FROM 3RD BUBBLE ------------------------------>

<?php

if ($customerpickup_ucbdelivering_flg == "2") {

	$rec_found = "n";
	$eml_sendon = "";
	$eml_sendby = "";
	$rec_found2 = "n";

	db();
	$getdata = db_query("Select customer_flg, email_sendon, email_sendby From loop_transaction_buyer_possible_delivery_delay where trans_rec_id = " . $_REQUEST["rec_id"] . " ");

	while ($getdata_row = array_shift($getdata)) {

		$rec_found2 = "y";

		$rec_found = "y";



		$eml_sendon = $getdata_row["email_sendon"];

		$eml_sendby = $getdata_row["email_sendby"];
	}

?>

<br><br>

<table cellSpacing="1" cellPadding="1" border="0" style="width: 400px">

    <tr align="middle">

        <?php if ($rec_found2 == "n") { ?>

        <td bgColor="#c0cdda" colSpan="2">

            <?php } else { ?>

        <td bgColor="#99FF99" colSpan="2">

            <?php } ?>

            <!-- <font size="1">Truck Fell Off?</font> -->

            <font size="1">POSSIBLE DELIVERY DELAY?</font>



        </td>

    </tr>

    <tr bgColor="#e4e4e4">

        <td align="center" height="13" colspan="2" class="style1">





            <?php if ($rec_found == "n") { ?>

            <input type="button" id="truckfellof_eml" value="Send Customer E-mail" onclick="reminder_popup_send_cust_email(<?php echo $_REQUEST[" ID"]; ?>,
            <?php echo $_REQUEST["rec_id"]; ?>,
            <?php echo $_REQUEST["warehouse_id"]; ?>,'
            <?php echo $_REQUEST["rec_type"]; ?>')">

            <?php 	} else {  ?>

            <input type="button" id="truckfellof_eml" value="Re-Send Customer E-mail" onclick="reminder_popup_send_cust_email(<?php echo $_REQUEST[" ID"]; ?>,
            <?php echo $_REQUEST["rec_id"]; ?>,
            <?php echo $_REQUEST["warehouse_id"]; ?>,'
            <?php echo $_REQUEST["rec_type"]; ?>')">

            <?php

					echo "Email Sent on " . $eml_sendon . " by " . $eml_sendby;
				}

				?>

        </td>

    </tr>

</table>

<br><br>

<?php } ?>

<!------------------------------ End Truck Fell off  FROM 3RD BUBBLE ------------------------------>



<!------------------------------ Start ORDER ISSUE PICTURES FROM 3RD BUBBLE ------------------------------>

<?php

if ($order_issue_val == 0) {
} else {

?>

<table cellSpacing="0" cellPadding="1" border="0" style="width: 400px">

    <tr align="middle">

        <?php if ($order_issue_pictures_val != 1) { ?>

        <td bgColor="#fb8a8a" colSpan="2">

            <?php } else { ?>

        <td bgColor="#99FF99" colSpan="2">

            <?php } ?>

            <!-- <font size="1">Truck Fell Off?</font> -->

            <font size="1">ORDER ISSUE PICTURES</font>



        </td>

    </tr>

    <?php

		$img_qry = "Select * from order_issue_pictures Where trans_id = " . $_REQUEST["rec_id"] . " ORDER by id ASC";

		db();
		$img_res = db_query($img_qry);

		if (tep_db_num_rows($img_res) > 0) {

		?>

    <tr bgColor="#e4e4e4">

        <td colSpan="2" id="orderissue_tbl">

            <table class="orderissue-style" border="0" style="width: 400px">

                <?php

						while ($img_row = array_shift($img_res)) {

						?>

                <tr bgColor="#e4e4e4" id="orderissue<?php echo $img_row[" id"]; ?>">

                    <td align="center" style="padding: 4px;" width="220px">

                        <a href="javascript:void(0);" onclick="view_orderissue_img('<?php echo 'orderissuepic/' . $img_row["order_img"]; ?>', <?php echo $img_row["
                            id"]; ?>);">

                            <img src="orderissuepic/<?php echo $img_row[" order_img"]; ?>" width="50" height="auto">

                        </a><br>



                    </td>

                    <td align="left" style="padding: 4px; padding-left: 12px;"><a
                            style='color:#E00003; text-decoration: none; font-weight: 600;' href="javascript:void(0);"
                            onclick="order_issue_img_delete(<?php echo $img_row[" id"]; ?>,
                            <?php echo $_REQUEST["rec_id"]; ?>,
                            <?php echo $_REQUEST["warehouse_id"]; ?>,
                            <?php echo $_REQUEST["ID"]; ?>)"> X
                        </a></td>

                </tr>

                <?php

						}

						?>

            </table>

        </td>

    </tr>

    <?php

		}

		?>

</table>

<form METHOD="POST" ENCTYPE="multipart/form-data" action="orderissue_picture_save.php">

    <table cellSpacing="0" cellPadding="5" border="0" style="width: 400px">

        <tr bgColor="#e4e4e4">

            <td align="center"><input type="file" name="File[]" size="10" multiple></td>

        </tr>

        <tr bgColor="#e4e4e4">



            <td align="center"><input style="cursor:pointer;" type="submit" value="Upload">

                <input type="hidden" name="comp_id" value="<?php echo $_REQUEST[" ID"]; ?>">

                <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>">

                <input type="hidden" id="rec_type" name="rec_type" value="<?php echo $_REQUEST[" rec_type"]; ?>">

                <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST[" warehouse_id"]; ?>">

            </td>

        </tr>

        <table>

</form>

<?php

}

?>

<!------------------------------ End ORDER ISSUE PICTURES FROM 3RD BUBBLE ------------------------------>

<br>

<div id="divbol_confirmshipmentreceipt">

    <Font Face='arial' size='2'>
        <Font Face='arial' size='2'>

            <br><br>



            <table cellSpacing="1" cellPadding="1" border="0" id="table5">



                <tr align="middle">
                    <td bgColor="#c0cdda" colSpan="8">
                        <p>
                            <font size="1">PREVIOUSLY CREATED/UPLOADED BILLS OF LADING</font>
                    </td>
                </tr>



                <tr bgColor="#e4e4e4">

                    <td height="13" style="width: 75px" class="style1" align="center">Planned Delivery Date</td>

                    <td height="13" style="width: 80px" class="style1" align="center">Ops Delivery Date</td>

                    <td align="center" height="13" style="width: 96px" class="style1">Actual Pickup Date</td>

                    <td align="center" height="13" style="width: 114px" class="style1">Confirm Delivery</td>

                    <?php if ($customerpickup_ucbdelivering_flg == "2") { ?>

                    <td align="center" height="13" style="width: 114px" class="style1">Delivery Confirmed Email</td>

                    <?php } ?>



                    <td align="center" height="13" style="width: 114px" class="style1">Proof of Delivery</td>

                    <td align="center" height="13" style="width: 200px" class="style1">B2B Survey</td>

                </tr>



                <?php

				$po_archive_date = "";

				$query = "SELECT variablevalue FROM tblvariable where variablename = 'po_archive_date'";

				db();
				$dt_view_res3 = db_query($query);

				while ($objQuote = array_shift($dt_view_res3)) {

					$po_archive_date = $objQuote["variablevalue"];
				}



				$b2b_survey_ignore = 0;
				$b2b_survey_ignore_by = "";
				$b2b_survey_ignore_on = "";
				$po_date = "";

				$ops_delivery_date = "";
				$Planned_delivery_date = "";
				$po_file = "";
				$bookeddeliverycost = 0;

				$sql = "SELECT * from loop_transaction_buyer where id = " . $_REQUEST["rec_id"];

				$sql_res = db_query($sql);

				while ($dt_view_row = array_shift($sql_res)) {



					$b2b_survey_ignore = $dt_view_row["b2b_survey_ignore"];

					$b2b_survey_ignore_by = $dt_view_row["b2b_survey_ignore_by"];

					$b2b_survey_ignore_on = $dt_view_row["b2b_survey_ignore_on"];



					$po_file = $dt_view_row["po_file"];

					$po_date = $dt_view_row["po_date"];



					if ($dt_view_row["ops_delivery_date"] == "") {

						$ops_delivery_date = "";
					} else {

						$ops_delivery_date = date("m/d/Y", strtotime($dt_view_row["ops_delivery_date"]));
					}



					if ($dt_view_row["po_delivery_dt"] != "") {

						$Planned_delivery_date = date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
					} else {

						$Planned_delivery_date = date("m/d/Y", strtotime($dt_view_row["po_delivery"]));
					}

					$bookeddeliverycost = $dt_view_row["po_freight"];



					$proof_of_delivery =  empty($dt_view_row["proof_of_delivery"]) ? '' : $dt_view_row["proof_of_delivery"];

					$pod_done_by =  empty($dt_view_row["pod_done_by"]) ? '' : $dt_view_row["pod_done_by"];

					$pod_done_on =  empty($dt_view_row["pod_done_on"]) ? '' : date("m/d/Y", strtotime($dt_view_row["pod_done_on"]));
				}



				$x = 0;
				$unsign_bol_file = "";
				$actual_pickup_date = "";
				$actual_delivery_date = "";
				$bol_signed_file_name = "";

				$so_view_qry = "SELECT * from loop_bol_files WHERE trans_rec_id LIKE '" . $_REQUEST["rec_id"] . "' AND bol_shipped = 1 ORDER BY id DESC";

				db();
				$so_view_res = db_query($so_view_qry);

				while ($rows = array_shift($so_view_res)) {

					$unsign_bol_file = $rows["file_name"];

					$bol_signed_file_name = $rows["bol_signed_file_name"];

					if ($rows["bol_shipped_date"] != "") {

						$actual_pickup_date = $rows["bol_shipped_date"] . " CT";
					}

					$actual_delivery_date = $rows["bol_shipment_received_date"];



					$x++;

				?>

                <tr bgColor="#e4e4e4">

                    <td height="13" class="style1" align="middle">

                        <?php echo  $Planned_delivery_date; ?>

                    </td>

                    <td height="13" class="style1" align="middle">

                        <?php echo  $ops_delivery_date; ?>

                    </td>

                    <td height="13" class="style1" align="middle">

                        <?php echo  $actual_pickup_date; ?>

                    </td>



                    <td align="center" height="13" class="style1">

                        <?php

							if ($rows["bol_shipment_received"] == 0) { ?>

                        <input type="button" value="Confirm Delivery" id="btnshowconfirmdelivery"
                            onclick="showconfirmdelivery()">

                        <?php

							} else {

								?>Confirmed By: <?php echo  $rows["bol_shipment_received_employee"]; ?><br><?php echo  $rows["bol_shipment_received_date"] . " CT"; ?>

                        <br />

                        <input type="button" value="Resend Confirm Delivery" id="btnshowconfirmdelivery"
                            onclick="showconfirmdelivery()">

                        <?php } ?>

                    </td>



                    <?php if ($customerpickup_ucbdelivering_flg == "2") { ?>

                    <td align="center" height="13" class="style1">

                        <?php

								if ($rows["bol_shipment_received"] == 1) {

									if ($rows["bol_confirm_delivery_email"] == 0) {

								?>

                        <input type="button" name="confirm_delivered_mail_button" id="confirm_delivered_mail_button"
                            value="Send Confirm Delivery Email" onclick="send_confirm_delivery_email(<?php echo $_REQUEST["
                            ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $_REQUEST["warehouse_id"]; ?> );">

                        <?php

									} else {

										?>Confirmed By:
                        <?php echo  $rows["bol_confirm_delivery_email_emp"]; ?><br><?php echo  date("m/d/Y", strtotime($rows["bol_confirm_delivery_email_date"])) . " CT"; ?>

                        <br />

                        <input type="button" name="confirm_delivered_mail_button" id="confirm_delivered_mail_button"
                            value="Resend Confirm Delivery Email" onclick="send_confirm_delivery_email(<?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $_REQUEST["warehouse_id"]; ?> );">

                        <?php

									}
								} ?>

                    </td>

                    <?php } ?>







                    <td align="center" height="13" class="style1">

                        <?php if ($proof_of_delivery == "") {

							?>

                        <form name="frmUploadPrfOfDelivry" action="addproofofdelivry_mrg.php" method="post"
                            onSubmit="return check_frmUploadPrfOfDelivry(pod);" encType="multipart/form-data">

                            <input type="hidden" name="warehouse_id" value="<?php echo $rows[" warehouse_id"]; ?>" />

                            <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />

                            <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />

                            <input type="hidden" name="bol_id" value="<?php echo $_REQUEST[" rec_id"]; ?>">

                            <input type=file name="filePOD" style="width:150px;">

                            <input style="cursor:pointer;" value="Proof of Delivery" type=submit>

                        </form>

                        <?php

							} else {

							?>

                        <a style="color:#0000FF;" target="_blank"
                            href="proof_of_delivery/<?php echo  $proof_of_delivery; ?>">View Uploaded By:</a><br>

                        <?php echo  $pod_done_by; ?><br><?php echo  date("m/d/Y H:i:s", strtotime($pod_done_on)) . " CT"; ?><br>

                        <form name="frmUploadPrfOfDelivry" action="addproofofdelivry_mrg.php" method="post"
                            onSubmit="return check_frmUploadPrfOfDelivry(pod);" encType="multipart/form-data">

                            <input type="hidden" name="warehouse_id" value="<?php echo $rows[" warehouse_id"]; ?>" />

                            <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />

                            <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />

                            <input type="hidden" name="bol_id" value="<?php echo $_REQUEST[" rec_id"]; ?>">

                            <input type=file name="filePOD" style="width:150px;">

                            <input style="cursor:pointer;" value="Re Upload Proof of Delivery" type=submit>

                        </form>



                        <?php

							}

							?>

                    </td>



                    <!-- /*********** Proof of delivery ends -->



                    <td align="center" height="13" class="style1">

                        <?php if ($rows["bol_shipment_followup"] == 0) { ?>

                        <?php if ($b2b_survey_ignore == 0) { ?>

                        <form action="bol_confirmshipmentfollowup_mrg.php" method=post>

                            <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>">

                            <input type="hidden" name="rec_type" value="<?php echo $_REQUEST[" rec_type"]; ?>">

                            <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>">

                            <input type="hidden" name="bol_id" value="<?php echo $_REQUEST[" rec_id"]; ?>">

                            <input type="hidden" name="userinitials" value="<?php echo $_COOKIE[" userinitials"]; ?>">

                            <input type="hidden" name="location" value="loopsreceived">

                            <input type="hidden" name="warehouse_id" value="<?php echo $rows[" warehouse_id"]; ?>">

                            <?php if (isset($freightupdates) == 0) {
											echo "<font color=red>OPT OUT</font>";
										} ?>



                            <input type="button" value="B2B Survey" style="cursor:pointer;" id="reminder_popup_set5_btn"
                                onclick="reminder_popup_set5(<?php echo $_REQUEST[" ID"]; ?>,
                            <?php echo $_REQUEST["rec_id"]; ?>,
                            <?php echo $_REQUEST["warehouse_id"]; ?>,'
                            <?php echo $_REQUEST["rec_type"]; ?>')">

                        </form>

                        <?php } else {



									if (isset($freightupdates) == 0) {
										echo "<font color=red>OPT OUT</font>";
									} ?>

                        <input type="button" value="Re-B2B Survey" style="cursor:pointer;" id="reminder_popup_set5_btn"
                            onclick="reminder_popup_set5(<?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $_REQUEST["warehouse_id"]; ?>,'
                        <?php echo $_REQUEST["rec_type"]; ?>')">

                        <?php

								}



								if ($b2b_survey_ignore == 0) { ?>

                        <form action="#" method="post">

                            <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>">

                            <input type="hidden" name="rec_type" value="<?php echo $_REQUEST[" rec_type"]; ?>">

                            <input type="hidden" name="rec_id" value="<?php echo $rows[" trans_rec_id"]; ?>">

                            <input type="hidden" name="bol_id" value="<?php echo $rows[" id"]; ?>">

                            <input type="hidden" name="userinitials" value="<?php echo $_COOKIE[" userinitials"]; ?>">

                            <input type="hidden" name="location" value="loopsreceived">

                            <input type="hidden" name="warehouse_id" value="<?php echo $rows[" warehouse_id"]; ?>">



                            <input type="button" id="btnsurvey_ignore" name="btnsurvey_ignore" value="Ignore" onclick="updateb2bsurveyignore(<?php echo $rows[" id"]; ?>,
                            <?php echo $rows["trans_rec_id"]; ?>,
                            <?php echo $_REQUEST["ID"]; ?>,
                            <?php echo $rows["warehouse_id"]; ?>)">

                        </form>

                        <?php

								} else {

									echo "B2B Survey ignore by " . $b2b_survey_ignore_by . " on " . $b2b_survey_ignore_on;
								}
							} else {



								if ($b2b_survey_ignore == 1) {

									echo "B2B Survey ignore by " . $b2b_survey_ignore_by . " on " . $b2b_survey_ignore_on . "<br>";
								} ?>



                        B2B Survey Send By:

                        <?php echo  $rows["bol_shipment_followup_employee"]; ?><br>

                        <?php echo  $rows["bol_shipment_followup_date"]; ?><br>



                        <?php if (isset($freightupdates) == 0) {
									echo "<font color=red>OPT OUT</font>";
								} ?>

                        <input type="button" value="Re-B2B Survey" style="cursor:pointer;" id="reminder_popup_set5_btn"
                            onclick="reminder_popup_set5(<?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $_REQUEST["warehouse_id"]; ?>,'
                        <?php echo $_REQUEST["rec_type"]; ?>')">



                        <?php

								$survey_response = "";

								$sql = "SELECT * FROM b2b_survey_response where trans_rec_id = " . $rows["trans_rec_id"] . " order by unqid";

								db();
								$result_new = db_query($sql);



								while ($myrowsel_new = array_shift($result_new)) {

									$emlfrom = "";

									if ($myrowsel_new["shiptosellto_flg"] == "sellto1" || $myrowsel_new["shiptosellto_flg"] == "shipto") {

										db_b2b();
										$result_b2b = db_query("Select email as selltoeml, shipemail from companyInfo where ID = " . $myrowsel_new["shiptosellto_id"]);



										while ($myrowsel_b2b = array_shift($result_b2b)) {

											if ($myrowsel_new["shiptosellto_flg"] == "sellto1") {

												$emlfrom = $myrowsel_b2b["selltoeml"];
											}



											if ($myrowsel_new["shiptosellto_flg"] == "shipto") {

												$emlfrom = $myrowsel_b2b["shipemail"];
											}
										}
									}



									if ($myrowsel_new["shiptosellto_flg"] == "sellto2") {

										db_b2b();
										$result_b2b = db_query("Select email from b2bsellto where selltoid = " . $myrowsel_new["shiptosellto_id"]);



										while ($myrowsel_b2b = array_shift($result_b2b)) {

											$emlfrom = $myrowsel_b2b["email"];
										}
									}



									$survey_response = $myrowsel_new["survey_response"];



									if ($emlfrom != "") {
										if ($survey_response == "Y") {
											echo "<br><font size='1'>Survey Response: <font color=green><b>Yes</b></font></font>, <font size='1'>submitted on " . date("m/d/Y H:i:s", strtotime($myrowsel_new["survey_response_date_time"])) . " CT by: " . $emlfrom . " </font><br>";
										} else {
											echo "<br><font size='1'>Survey Response: <font color=red><b>No</b></font></font>, <font size='1'>submitted on " . date("m/d/Y H:i:s", strtotime($myrowsel_new["survey_response_date_time"])) . " CT by: " . $emlfrom . ", <br><br>Comments: " . $myrowsel_new["survey_response_remark"] . "</font><br>";
										}
									} else {
										if ($survey_response == "Y") {
											echo "<br><font size='1'>Survey Response: <font color=green><b>Yes</b></font></font>, <font size='1'>submitted on " . date("m/d/Y H:i:s", strtotime($myrowsel_new["survey_response_date_time"])) . " CT</font><br>";
										} else {
											echo "<br><font size='1'>Survey Response: <font color=red><b>No</b></font></font>, <font size='1'>submitted on " . date("m/d/Y H:i:s", strtotime($myrowsel_new["survey_response_date_time"])) . " CT, <br><br>Comments: " . $myrowsel_new["survey_response_remark"] . "</font><br>";
										}
									}
								}





								if ($survey_response == "") {

									echo "<br><font size='1'><b>Survey</b>: No Response</font>";
								}

								?>

                        <?php } ?>

                    </td>



                </tr>



            </table>



            <br>
        </font>
    </font>



    <div id="div_bol_confirmshipmentreceipt" style="display:none;">

        <form action="search_result_include_buyer_received_mrg_mysqli.php" method="post"
            name="frmbol_confirmshipmentreceipt" id="frmbol_confirmshipmentreceipt">

            <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>">

            <input type="hidden" name="rec_type" value="<?php echo $_REQUEST[" rec_type"]; ?>">

            <input type="hidden" name="rec_id" value="<?php echo $rows[" trans_rec_id"]; ?>">

            <input type="hidden" name="bol_id" value="<?php echo $rows[" id"]; ?>">

            <input type="hidden" name="userinitials" value="<?php echo $_COOKIE[" userinitials"]; ?>">

            <input type="hidden" name="location" value="loopsreceived">

            <input type="hidden" name="warehouse_id" value="<?php echo $rows[" warehouse_id"]; ?>">

            <table>

                <tr>
                    <td colspan="2" bgColor="#c0cdda">
                        <font size="1">Confirm Delivery Costs</font>
                    </td>
                </tr>

                <tr>

                    <td>
                        <font size="1">Booked Delivery Cost:</font>
                    </td>

                    <td><input type="text" name="txtbookeddeliverycost" id="txtbookeddeliverycost"
                            value="<?php echo $bookeddeliverycost; ?>"></td>

                </tr>

                <tr>

                    <td>
                        <font size="1">Other Delivery Costs</font>
                    </td>

                    <td><input type="text" name="txtbol_other_freight_cost" id="txtbol_other_freight_cost" value="">
                    </td>

                </tr>

                <tr>
                    <td colspan="2">
                        <font size="1"><input type="button" name="btnconfrmbookeddeliverycost"
                                id="btnconfrmbookeddeliverycost" onclick="updatebolflgs(<?php echo $rows[" id"]; ?>,
                            <?php echo $rows["trans_rec_id"]; ?>,
                            <?php echo $_REQUEST["ID"]; ?>,
                            <?php echo $rows["warehouse_id"]; ?>)" value="Submit">
                        </font>
                    </td>
                </tr>

            </table>

        </form>

    </div>



    <?php } ?>

</div>



Purchase Order

<?php if ($po_file != "") {



	$archeive_date = new DateTime(date("Y-m-d", strtotime($po_archive_date)));

	$po_date_new = new DateTime(date("Y-m-d", strtotime($po_date)));



	if ($po_date_new < $archeive_date) {



		echo "<a target='_blank' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/Loopsfilebackup/Shared%20Documents/po/" . $po_file . "'>" . $po_file . "</a>";
	} else {

?>

&nbsp;&nbsp;&nbsp;

<a target="_blank" href='https://loops.usedcardboardboxes.com/po/<?php echo $po_file; ?>'>
    <?php echo $po_file; ?>
</a>

<div id="fileview"></div>

<script type="text/javascript">
display_file('https://loops.usedcardboardboxes.com/po/<?php echo $po_file; ?>');
</script>

<?php

	}

	?>

<?php } ?>



<br>

Unsigned BOL

<?php if ($unsign_bol_file != "") { ?>

&nbsp;&nbsp;&nbsp;

<a target="_blank" href='https://loops.usedcardboardboxes.com/bol/<?php echo $unsign_bol_file; ?>'>
    <?php echo $unsign_bol_file; ?>
</a>



&nbsp;&nbsp;&nbsp;<div id="fileview2"></div>

<script type="text/javascript">
display_file2('https://loops.usedcardboardboxes.com/bol/<?php echo $unsign_bol_file; ?>');
</script>

<?php } ?>



<br>

Signed BOL

<?php if ($bol_signed_file_name != "") { ?>

&nbsp;&nbsp;&nbsp;

<a target="_blank" href='https://loops.usedcardboardboxes.com/signedbols/<?php echo $bol_signed_file_name; ?>'>
    <?php echo $bol_signed_file_name; ?>
</a>



&nbsp;&nbsp;&nbsp;<div id="fileview3"></div>

<script type="text/javascript">
display_file3('https://loops.usedcardboardboxes.com/signedbols/<?php echo $bol_signed_file_name; ?>');
</script>

<?php } ?>