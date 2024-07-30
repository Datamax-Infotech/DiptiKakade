<style>
.black_overlay {
    display: none;
    position: absolute;
    top: 0%;
    left: 0%;
    width: 100%;
    height: 750px;
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
    width: 50%;
    height: 750px;
    padding: 16px;
    border: 1px solid gray;
    background-color: white;
    z-index: 1002;
    overflow: auto;
}

table.orderissue-style {
    border-collapse: collapse;
}

table.orderissue-style tr {
    border-top: 1px solid #FFF;
    border-bottom: 1px solid #FFF;
}
</style>

<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">
document.write(getCalendarStyles());
</script>
<script LANGUAGE="JavaScript">
var cal1xx = new CalendarPopup("listdiv");
cal1xx.showNavigationDropdowns();
var cal2xx = new CalendarPopup("listdiv2");
cal2xx.showNavigationDropdowns();
var cal3xx = new CalendarPopup("listdiv3");
cal3xx.showNavigationDropdowns();
var cal4xx = new CalendarPopup("listdiv");
cal4xx.showNavigationDropdowns();
</script>
<script>
function btnsendmail_pickup_appointment() {
    var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

    tmp_element1 = document.getElementById("txtemailto").value;
    tmp_element2 = document.getElementById("email_reminder_sch_p");
    tmp_element3 = document.getElementById("txtemailcc").value;
    tmp_element4 = document.getElementById("txtemailsubject").value;
    tmp_element5 = document.getElementById("hidden_reply_eml");

    if (tmp_element1.value == "") {
        alert("Please enter the To Email address.");
        return false;
    }

    if (tmp_element4.value == "") {
        alert("Please enter the Email Subject.");
        return false;
    }

    if (tmp_element3.value == "") {
        alert("Please enter the Cc Email address.");
        return false;
    }


    var inst = FCKeditorAPI.GetInstance("txtemailbody");
    var emailtext = inst.GetHTML();

    tmp_element5.value = emailtext;
    //alert(tmp_element5.value);
    document.getElementById("hidden_sendemail").value = "inemailmode";
    tmp_element2.submit();
    document.getElementById('light').style.display = 'none';
}

function booked_dock_appointment() {

    var ele_i = document.getElementById("pickup_dock_appointment_date").value;
    var ele_f = document.getElementById("pickup_dock_appointment_booked_frm").value;
    if (ele_i == "") {
        alert("Please enter the date.");
        return false;
    } else {
        ele_f.submit();
    }
}

function confirm_ship_chkfrm() {
    var ele_i = document.getElementById("confirm_ship_date").value;

    if (ele_i == "") {
        alert("Please enter the date.");
        return false;
    } else {
        return true;
    }
}

function delivery_dock_appoitment_sub() {
    var ele_i = document.getElementById("delivery_dock_appt_date").value;

    if (ele_i == "") {
        alert("Please enter the date.");
        return false;
    } else {
        return true;
    }
}
</script>
<script>
function pickup_appointment_sendmail(compid, rec_id, warehouse_id, rec_type, mail_type) {
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            selectobject = document.getElementById("pickup_appointment_email");
            n_left = f_getPosition(selectobject, 'Left');
            n_top = f_getPosition(selectobject, 'Top');

            document.getElementById("light").innerHTML = xmlhttp.responseText;
            document.getElementById('light').style.display = 'block';

            document.getElementById('light').style.left = (n_left - 230) + 'px';
            document.getElementById('light').style.top = n_top + 50 + 'px';
            document.getElementById('light').style.width = 700 + 'px';
        }
    }

    xmlhttp.open("POST", "sendemail_pickup_appointment.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
        warehouse_id + "&rec_type=" + rec_type + "&mail_type=" + mail_type, true);
    xmlhttp.send();
}

function confirm_ship_email_sent(compid, rec_id, warehouse_id, rec_type, mail_type) {
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            selectobject = document.getElementById("confirm_ship_email");
            n_left = f_getPosition(selectobject, 'Left');
            n_top = f_getPosition(selectobject, 'Top');

            document.getElementById("light").innerHTML = xmlhttp.responseText;
            document.getElementById('light').style.display = 'block';

            document.getElementById('light').style.left = (n_left - 230) + 'px';
            document.getElementById('light').style.top = n_top + 50 + 'px';
            document.getElementById('light').style.width = 700 + 'px';
        }
    }

    xmlhttp.open("POST", "sendemail_confirm_ship.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
        warehouse_id + "&rec_type=" + rec_type + "&mail_type=" + mail_type, true);
    xmlhttp.send();
}

function pickup_appointment_sendmail_ignore(compid, rec_id, warehouse_id, rec_type, ignore_type) {
    if (ignore_type == 'pickupappointignore') {
        document.getElementById("pickup_appointment_sent_email").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
    }

    if (ignore_type == 'cnfrshipemailignore') {
        document.getElementById("confirm_ship_sent_email").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
    }

    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (ignore_type == 'pickupappointignore') {
                document.getElementById("pickup_appointment_sent_email").innerHTML = xmlhttp.responseText;
            }

            if (ignore_type == 'cnfrshipemailignore') {
                document.getElementById("confirm_ship_sent_email").innerHTML = xmlhttp.responseText;
            }

        }
    }

    if (ignore_type == 'pickupappointignore') {
        xmlhttp.open("POST", "pickup_appointment_ignore_save.php?pickup_appointment_ignore=1&ID=" + compid +
            "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
    }

    if (ignore_type == 'cnfrshipemailignore') {
        xmlhttp.open("POST", "pickup_appointment_ignore_save.php?pickup_appointment_ignore=2&ID=" + compid +
            "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
    }

    xmlhttp.send();

}
//
function order_issue_img_delete(order_img_id, rec_id, warehouse_id, compid) {
    var rec_type = document.getElementById("rec_type").value;

    if (confirm("Do you want to delete selected picture?") == true) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById("orderissue_tbl").innerHTML = xmlhttp.responseText;

            }
        }

        xmlhttp.open("POST", "order_issue_imgs_delete.php?order_img_id=" + order_img_id + "&compid=" + compid +
            "&rec_id=" + rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type + "&delete=yes", true);
        xmlhttp.send();
    }
}

function view_orderissue_img(boximg, imgid) {
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
}

//
</script>

<div id="light" class="white_content"></div>
<div id="fade" class="black_overlay"></div>

<font Face='arial' size='2'>

    <br>

    <?php

    $DockAppointment = "";
    $DockAppointment_delivery = "";
    $vendor_nm = "";
    $MG_sort_array = array();
    $tr_color_css = "";
    $firstrec_flg = "";
    $sales_warehouseid = "";
    $order_issue_pictures_val = "";
    $sales_rec_type = "";
    $sales_tansid = "";
    $sales_company_id = "";
    $usrfile = "";


    $rec_id = $_REQUEST["rec_id"];
    $dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "'";
    db();
    $dt_view_res = db_query($dt_view_qry);
    $trailer_view_row = array_shift($dt_view_res);
    $trailer_number = $trailer_view_row["pr_trailer"];
    $pa_warehouse = $trailer_view_row["pa_warehouse"];
    $pr_recycling = $trailer_view_row["pr_recycling"];
    //
    $pr_ucblot = $trailer_view_row["pr_ucblot"];
    $pr_ucblot_dt = $trailer_view_row["pr_ucblot_dt"];
    $pr_ucblot_by = $trailer_view_row["pr_ucblot_by"];
    $pr_ucblot_note = $trailer_view_row["pr_ucblot_note"];
    //
    $srt_dockdoors_flg = $trailer_view_row["srt_dockdoors_flg"];
    $srt_dock_doors = $trailer_view_row["srt_dock_doors"];
    $srt_ucbdockdoor_note = $trailer_view_row["srt_ucbdockdoor_note"];
    $srt_ucbdockdoor_by = $trailer_view_row["srt_ucbdockdoor_by"];
    $srt_ucbdockdoor_dt = $trailer_view_row["srt_ucbdockdoor_dt"];
    //
    $ucbunloaded_flg = $trailer_view_row["ucbunloaded_flg"];
    $ucbunloaded_dt = $trailer_view_row["ucbunloaded_dt"];
    $ucbunloaded_by = $trailer_view_row["ucbunloaded_by"];
    $ucbunloaded_note = $trailer_view_row["ucbunloaded_note"];

    $area_unloaded = $trailer_view_row["area_unloaded"];

    $pickup_appointment_email_sent = $trailer_view_row["pickup_appointment_email_sent"];
    $pickup_appointment_email_emp = $trailer_view_row["pickup_appointment_email_emp"];
    $pickup_appointment_email_date = $trailer_view_row["pickup_appointment_email_date"];
    $pickup_appointment_email_ignore = $trailer_view_row["pickup_appointment_email_ignore"];

    $pickup_dock_appointment_date = $trailer_view_row["pickup_dock_appointment_date"];
    $booked_pickup_time_hours = $trailer_view_row["pickup_dock_appointment_time_hr"];
    $booked_pickup_time_minutes = $trailer_view_row["pickup_dock_appointment_time_min"];
    $booked_pickup_time_format = $trailer_view_row["pickup_dock_appointment_time_frt"];
    $booked_pickup_time_emp = $trailer_view_row["pickup_dock_appointment_time_emp"];
    $booked_pickup_time_udate = $trailer_view_row["pickup_dock_appointment_time_udate"];
    $booked_pickup_time_ignore = $trailer_view_row["pickup_dock_appointment_time_ignore"];

    if ($trailer_view_row["confirm_ship_date"] != "" && $trailer_view_row["confirm_ship_date"] != "0000-00-00 00:00:00") {
        $confirm_ship_date = date("m/d/Y", strtotime($trailer_view_row["confirm_ship_date"]));
    } else {
        $confirm_ship_date = "";
    }
    $confirm_ship_emp_by = $trailer_view_row["confirm_ship_emp_by"];
    if ($trailer_view_row["confirm_ship_emp_on"] != "" && $trailer_view_row["confirm_ship_emp_on"] != "0000-00-00 00:00:00") {
        $confirm_ship_emp_on = date("m/d/Y", strtotime($trailer_view_row["confirm_ship_emp_on"]));
    } else {
        $confirm_ship_emp_on = "";
    }

    $confirm_ship_email_sent_flg = $trailer_view_row["confirm_ship_email_sent_flg"];
    $confirm_ship_email_sent_emp_by = $trailer_view_row["confirm_ship_email_sent_emp_by"];
    $confirm_ship_email_sent_emp_on = $trailer_view_row["confirm_ship_email_sent_emp_on"];
    $confirm_ship_email_sent_ignore = $trailer_view_row["confirm_ship_email_sent_ignore"];

    $delivery_dock_appt_date = $trailer_view_row["delivery_dock_appt_date"];
    $delivery_dock_appt_hr = $trailer_view_row["delivery_dock_appt_hr"];
    $delivery_dock_appt_min = $trailer_view_row["delivery_dock_appt_min"];
    $delivery_dock_appt_frt = $trailer_view_row["delivery_dock_appt_frt"];
    $delivery_dock_appt_empby = $trailer_view_row["delivery_dock_appt_empby"];
    $delivery_dock_appt_ondt = $trailer_view_row["delivery_dock_appt_ondt"];
    $delivery_dock_appt_ignore = $trailer_view_row["delivery_dock_appt_ignore"];

    //For virtual trans
    $virtual_sales_trans_id = 0;
    $sql1 = "SELECT id from loop_transaction_buyer where virtual_inventory_trans_id = '" . $rec_id . "'";
    db();
    $result1 = db_query($sql1);
    while ($myrowsel1 = array_shift($result1)) {
        $virtual_sales_trans_id = $myrowsel1["id"];
    }

    $DockAppointment_dt = "";
    $DockAppointment_delivery_dt = "";
    if ($virtual_sales_trans_id > 0) {
        $sql1 = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id= '" . $virtual_sales_trans_id . "'";
        db();
        $result1 = db_query($sql1);
        while ($myrowsel1 = array_shift($result1)) {
            $DockAppointment_dt = $myrowsel1["date"];
            $pickup_dock_appointment_date = $myrowsel1["date"];
            $DockAppointment = $myrowsel1["time"];
            $booked_pickup_time_udate = $myrowsel1["entry_date_time"];
            if ($myrowsel1["time"] != "") {
                $booked_pickup_time_hours = date("h", strtotime($myrowsel1["time"]));
                $booked_pickup_time_minutes = date("i", strtotime($myrowsel1["time"]));
                $booked_pickup_time_format = date("A", strtotime($myrowsel1["time"]));
            }
        }

        $delivery_dock_appt_hr = "";
        $delivery_dock_appt_min = "";
        $delivery_dock_appt_frt = "";
        $sql1 = "SELECT * FROM loop_transaction_freight_delivery WHERE trans_rec_id = '" . $virtual_sales_trans_id . "'";
        db();
        $result1 = db_query($sql1);
        while ($myrowsel1 = array_shift($result1)) {
            $DockAppointment_delivery_dt = $myrowsel1["date"];
            $delivery_dock_appt_date = $myrowsel1["date"];
            $DockAppointment_delivery = $myrowsel1["time"];
            $delivery_dock_appt_ondt = $myrowsel1["entry_date_time"];
            if ($myrowsel1["time"] != "") {
                $delivery_dock_appt_hr = date("h", strtotime($myrowsel1["time"]));
                $delivery_dock_appt_min = date("i", strtotime($myrowsel1["time"]));
                $delivery_dock_appt_frt = date("A", strtotime($myrowsel1["time"]));
            }
        }
    }
    //For virtual trans


    if ($pa_warehouse == "238") $sortingwarehouse = "directship";              // id 238 for warehouse name Direct Ship in loop_warehouse table.

    ?>

    <?php


    if ($booked_pickup_time_emp == "" && $booked_pickup_time_ignore == "" && $DockAppointment_dt == "" || $_REQUEST['pickup_dock_appointment_date_edit'] == "true") {
    ?>

    <form id="pickup_dock_appointment_booked_frm" name="pickup_dock_appointment"
        action="pickup_appointment_booked_date_save.php" method="post">
        <table cellSpacing="1" cellPadding="1" border="0" width="600px">
            <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
            <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
            <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
            <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />

            <tr align="middle">
                <?php

                    if ($_REQUEST['pickup_dock_appointment_date_edit'] == "true") {
                        $setbgcolor = "#FFF1CB";
                    } else {
                        $setbgcolor = "#FB8A8A";
                    }
                    ?>
                <td bgColor="<?php echo $setbgcolor ?>" colspan="2">
                    <font size="1">PICKUP DOCK APPOINTMENT</font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width:205px;" class="style1">
                    Booked Pickup Date
                </td>
                <td align="left" height="13" class="style1">
                    <input type="text" id="pickup_dock_appointment_date" name="pickup_dock_appointment_date" size="20"
                        value="<?php if ($pickup_dock_appointment_date != "") {
                                                                                                                                        echo date("m/d/Y", strtotime($pickup_dock_appointment_date));
                                                                                                                                    } else {
                                                                                                                                        echo date("m/d/Y");
                                                                                                                                    } ?>">
                    <a href="#"
                        onclick="cal1xx.select(document.pickup_dock_appointment.pickup_dock_appointment_date,'anchor1xx','MM/dd/yyyy'); return false;"
                        name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
                    <div ID="listdiv"
                        STYLE="position:absolute;visibility:hidden;background-color:white;background-color:white;">
                    </div>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" class="style1" style="width:205px;">
                    Booked Pickup Time
                </td>
                <td align="left" height="13" class="style1">
                    <select name="booked_pickup_time_hours">
                        <?php
                            for ($booked_pickup_time_hr = 0; $booked_pickup_time_hr < 12; $booked_pickup_time_hr++) {
                                //echo '<option value="'.substr('0'.$booked_pickup_time_hr, -2).'">'.substr('0'.$booked_pickup_time_hr, -2).'</option>';
                                echo '<option ' . ((substr('0' . $booked_pickup_time_hr, -2) == $booked_pickup_time_hours) ? "Selected" : "") . '>' . substr('0' . $booked_pickup_time_hr, -2) . '</option>';
                            }
                            ?>
                    </select> <b>:</b>
                    <select name="booked_pickup_time_minutes">
                        <?php
                            for ($booked_pickup_time_min = 0; $booked_pickup_time_min < 60; $booked_pickup_time_min += 5) {
                                echo '<option ' . ((substr('0' . $booked_pickup_time_min, -2) == $booked_pickup_time_minutes) ? "Selected" : "") . '>' . substr('0' . $booked_pickup_time_min, -2) . '</option>';
                            }
                            ?>
                    </select>&nbsp;
                    <select name="booked_pickup_time_format">
                        <option <?php echo (($delivery_dock_appt_frt == "AM") ? "Selected" : ""); ?>>AM</option>
                        <option <?php echo (($delivery_dock_appt_frt == "PM") ? "Selected" : ""); ?>>PM</option>
                    </select>

                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan="2" class="style1">
                    <?php

                        if ($_REQUEST['pickup_dock_appointment_date_edit'] == "true") {
                        ?>
                    <input type="submit" name="submit" value="Submit" onclick="return booked_dock_appointment();" />
                    &emsp;&emsp;
                    <input type="button" onclick="javascript: window.history.go(-1)" value="Ignore" />
                    <?php

                        } else {
                        ?>
                    <input type="submit" name="submit" value="Submit" onclick="return booked_dock_appointment();" />
                    &emsp;&emsp;
                    <input type="submit" name="submit" value="Ignore" />
                    <?php

                        }
                        ?>
                </td>
            </tr>
        </table>
    </form>
    <?php

    } else {
    ?>
    <table cellSpacing="1" cellPadding="1" border="0" width="600px">
        <tr align="middle">
            <td bgColor="#99ff99" colspan="2">
                <font size="1">PICKUP DOCK APPOINTMENT <a
                        href="viewCompany_func1_transaction.php?ID=<?php echo $_REQUEST['ID']; ?>&show=transactions&warehouse_id=<?php echo isset($id); ?>&rec_id=<?php echo $rec_id; ?>&rec_type=<?php echo isset($rec_type); ?>&proc=View&display=seller_sort&pickup_dock_appointment_date_edit=true">EDIT</a>
                </font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width:205px;" class="style1">
                Booked Pickup Date
            </td>
            <td align="left" height="13" class="style1">
                <?php echo (($pickup_dock_appointment_date == "0000-00-00 00:00:00") ? "" : date("m/d/Y", strtotime($pickup_dock_appointment_date))) ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width:205px" class="style1">
                Booked Pickup Time
            </td>
            <td align="left" height="13" class="style1">
                <?php

                    if ($DockAppointment != "") {
                        echo $DockAppointment;
                    } elseif ($booked_pickup_time_hours == 0 && $booked_pickup_time_minutes == 0) {
                        echo "";
                    } else {
                        echo substr('0' . $booked_pickup_time_hours, -2) . " : " . substr('0' . $booked_pickup_time_minutes, -2) . "  " . $booked_pickup_time_format;
                    }
                    ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" class="style1" style="width:205px;">
                Employee
            </td>
            <td align="left" height="13" class="style1">
                <?php

                    $sql = "SELECT * FROM loop_employees WHERE id=? and status = 'Active'";
                    $result = db_query($sql, array("s"), array($booked_pickup_time_emp));
                    $rq = array_shift($result);
                    echo $rq['initials'];
                    ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" style="width:205px;">
                Date/Time
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $booked_pickup_time_udate; ?>
            </td>
        </tr>
        <?php

            if ($booked_pickup_time_ignore == 1) {
                echo "<tr bgColor='#e4e4e4'><td align='center' height='13' colspan='2' class='style1'>";
                echo "Step has been Ignore by " . $rq['initials'] . " on " . $booked_pickup_time_udate . " CT";
                echo "</td></tr>";
            }
            ?>
    </table>

    <?php

    } // else part closed for PICKUP DOCK APPOINTMENT

    ?>




    <?php

    if (($delivery_dock_appt_empby == "" && $delivery_dock_appt_ignore == "" && $DockAppointment_delivery_dt == "") || $_REQUEST['delivery_dock_appt_dtedit'] == "true") {
    ?>
    <br><br>
    <form id="delivery_dock_appt" name="delivery_dock_appt" action="delivery_dock_appt_save.php" method="post">
        <table cellSpacing="1" cellPadding="1" border="0" width="600px">
            <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
            <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
            <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
            <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />

            <tr align="middle">
                <?php

                    if ($_REQUEST['delivery_dock_appt_dtedit'] == "true") {
                        $setbgcolor = "#FFF1CB";
                    } else {
                        $setbgcolor = "#FB8A8A";
                    }
                    ?>
                <td bgColor="<?php echo $setbgcolor ?>" colspan="2">
                    <font size="1">DELIVERY DOCK APPOINTMENT</font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width:205px;" class="style1">
                    Booked Delivery Date
                </td>
                <td align="left" height="13" class="style1">
                    <input type="text" id="delivery_dock_appt_date" name="delivery_dock_appt_date" size="20"
                        value="<?php if ($delivery_dock_appt_date != "") {
                                                                                                                            echo date("m/d/Y", strtotime($delivery_dock_appt_date));
                                                                                                                        } else {
                                                                                                                            echo date("m/d/Y");
                                                                                                                        } ?>">
                    <a href="#"
                        onclick="cal3xx.select(document.delivery_dock_appt.delivery_dock_appt_date,'anchor3xx','MM/dd/yyyy'); return false;"
                        name="anchor3xx" id="anchor3xx"><img border="0" src="images/calendar.jpg"></a>
                    <div ID="listdiv3"
                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" class="style1">
                    Booked Delivery Time
                </td>
                <td align="left" height="13" class="style1">
                    <select name="delivery_dock_appt_hr">
                        <?php

                            for ($booked_pickup_time_hr = 0; $booked_pickup_time_hr < 12; $booked_pickup_time_hr++) {
                                echo '<option ' . ((substr('0' . $booked_pickup_time_hr, -2) == $delivery_dock_appt_hr) ? "Selected" : "") . '>' . substr('0' . $booked_pickup_time_hr, -2) . '</option>';
                            }
                            ?>
                    </select> <b>:</b>
                    <select name="delivery_dock_appt_min">
                        <?php

                            for ($booked_pickup_time_min = 0; $booked_pickup_time_min < 60; $booked_pickup_time_min += 5) {
                                echo '<option ' . ((substr('0' . $booked_pickup_time_min, -2) == $delivery_dock_appt_min) ? "Selected" : "") . '>' . substr('0' . $booked_pickup_time_min, -2) . '</option>';
                            }
                            ?>
                    </select>&nbsp;
                    <select name="delivery_dock_appt_frt">
                        <option <?php echo (($delivery_dock_appt_frt == "AM") ? "Selected" : ""); ?>>AM</option>
                        <option <?php echo (($delivery_dock_appt_frt == "PM") ? "Selected" : ""); ?>>PM</option>
                    </select>

                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan="2" class="style1">
                    <input type="submit" name="submit" value="Submit"
                        onclick="return delivery_dock_appoitment_sub();" />
                    &emsp;&emsp;
                    <?php
                        if ($_REQUEST['delivery_dock_appt_dtedit'] == "true") {
                        ?>
                    <input type="button" onclick="javascript: window.history.go(-1)" value="Ignore" />
                    <?php
                        } else {
                        ?>
                    <input type="submit" name="submit" value="Ignore" />
                    <?php
                        }
                        ?>
                </td>
            </tr>
        </table>
    </form>
    <?php
    } else {
    ?>
    <br><br>
    <table cellSpacing="1" cellPadding="1" border="0" width="600px">
        <tr align="middle">
            <td bgColor="#99ff99" colspan="2">
                <font size="1">DELIVERY DOCK APPOINTMENT <a
                        href="viewCompany_func1_transaction.php?ID=<?php echo $_REQUEST['ID']; ?>&show=transactions&warehouse_id=<?php echo isset($id); ?>&rec_id=<?php echo $rec_id; ?>&rec_type=<?php echo isset($rec_type); ?>&proc=View&display=seller_sort&delivery_dock_appt_dtedit=true">EDIT</a>
                </font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width:205px;" class="style1">
                Booked Delivery Date
            </td>
            <td align="left" height="13" class="style1">
                <?php echo (($delivery_dock_appt_date == "0000-00-00 00:00:00") ? "" : date("m/d/Y", strtotime($delivery_dock_appt_date))) ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 205px" class="style1">
                Booked Delivery Time
            </td>
            <td align="left" height="13" class="style1">
                <?php

                    if ($DockAppointment_delivery != "") {
                        echo $DockAppointment_delivery;
                    } else if ($delivery_dock_appt_ignore == 1) {
                        echo "";
                    } else if ($delivery_dock_appt_hr != "") {
                        echo substr('0' . $delivery_dock_appt_hr, -2) . " : " . substr('0' . $delivery_dock_appt_min, -2) . "  " . $delivery_dock_appt_frt;
                    }
                    ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" class="style1">
                Employee
            </td>
            <td align="left" height="13" class="style1">
                <?php

                    $sql = "SELECT * FROM loop_employees WHERE id=? and status = 'Active'";
                    $result = db_query($sql, array("s"), array($delivery_dock_appt_empby));
                    $rq = array_shift($result);
                    echo $rq['initials'];
                    ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" class="style1">
                Date/Time
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $delivery_dock_appt_ondt; ?>
            </td>
        </tr>
        <?php

            if ($booked_pickup_time_ignore == 1) {
                echo "<tr bgColor='#e4e4e4'><td align='center' height='13' colspan='2' class='style1'>";
                echo "Step has been Ignore by " . $rq['initials'] . " on " . $delivery_dock_appt_ondt . " CT";
                echo "</td></tr>";
            }
            ?>
    </table>

    <?php

    } // else part closed for PICKUP DOCK APPOINTMENT

    ?>


    <br>
    <?php
    ###############################################################################################
    //echo "pr_recycling - " . $pr_recycling . " - " . $pa_warehouse . "<br>";
    if ($pa_warehouse != 238) {
        if ($pr_recycling == 0) {
            $lotedit = $_REQUEST["lotedit"];
            if ($pr_ucblot == 0 || $lotedit == "true") {
    ?>
    <form METHOD="POST" action="ucblot_note_save.php">
        <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
        <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
        <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
        <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />

        <input type="hidden" name="rec_id_lotnote" value="<?php echo $_REQUEST["rec_id"]; ?>">
        <input type="hidden" name="lotnote_wid" value="<?php echo $_REQUEST["id"]; ?>">
        <input type="hidden" name="lotnote" value="yes">
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 600px">
            <tr align="middle">
                <?php

                            if ($_REQUEST['lotedit'] == "true") {
                                $setbgcolor = "#FFF1CB";
                            } else {
                                $setbgcolor = "#FB8A8A";
                            }
                            ?>
                <td bgColor="<?php echo $setbgcolor; ?>" colspan="2">
                    <font size="1">DROPPED IN UCB PARKING LOT</font>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width: 205px" class="style1">
                    Note
                </td>
                <td align="left" height="13" class="style1">
                    <?php if ($_REQUEST["lotedit"] == "true") {
                                ?>
                    <input type="text" value="<?php echo $pr_ucblot_note; ?>" id="pr_ucblot_note" name="pr_ucblot_note">
                    <?php
                                } else {
                                ?>
                    <input type="text" value="" id="pr_ucblot_note" name="pr_ucblot_note">
                    <?php
                                }
                                ?>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan="2" class="style1">
                    <input type="submit" value="Submit" id="updatelotnote" name="updatelotnote">
                    &nbsp;&nbsp;&nbsp;
                    <?php if ($_REQUEST["lotedit"] == "true") {
                                ?>
                    <a href="javascript: window.history.go(-1)">Ignore</a>
                    <?php
                                }
                                ?>

                </td>
            </tr>
        </table>
    </form>
    <?php
            } else {
            ?>
    <table cellSpacing="1" cellPadding="1" border="0" style="width: 600px">
        <tr align="middle">
            <td bgColor="#99FF99" colspan="2">
                <font size="1">DROPPED IN UCB PARKING LOT- <a
                        href="viewCompany_func1_transaction.php?ID=<?php echo $_REQUEST['ID']; ?>&show=transactions&warehouse_id=<?php echo isset($id); ?>&rec_id=<?php echo $rec_id; ?>&rec_type=<?php echo isset($rec_type); ?>&proc=View&display=seller_sort&lotedit=true">EDIT</a>
                </font>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Note
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $pr_ucblot_note; ?>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Employee:
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $pr_ucblot_by; ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Date/Time:
            </td>
            <td align="left" height="13" class="style1">
                <?php echo date("m/d/Y H:i:s", strtotime($pr_ucblot_dt)) . " CT"; ?>
            </td>
        </tr>
    </table>
    <?php
            }
        }
    }
    ?>
    <br>
    <!-- End UCB parking lot-->
    <!--Place in UCB dockdoor table-->
    <?php
    if ($pa_warehouse != 238) {
        if ($pr_recycling == 0) {

            $dockdooredit = $_REQUEST["dockdooredit"];
            if ($srt_dockdoors_flg == 0 || $dockdooredit == "true") {
    ?>
    <form METHOD="POST" action="ucbdockdoor_save.php">
        <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
        <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
        <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
        <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />

        <input type="hidden" name="rec_id_dockdoor" value="<?php echo $_REQUEST["rec_id"]; ?>">
        <input type="hidden" name="dockdoornote_wid" value="<?php echo $_REQUEST["id"]; ?>">
        <input type="hidden" name="dockdoornoteflg" value="yes">
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 600px">
            <tr align="middle">
                <?php
                            if ($_REQUEST['dockdooredit'] == "true") {
                                $setbgcolor = "#FFF1CB";
                            } else {
                                $setbgcolor = "#FB8A8A";
                            }
                            ?>
                <td bgColor="<?php echo $setbgcolor ?>" colspan="2">
                    <font size="1">PLACED IN UCB DOCK DOOR</font>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width: 205px" class="style1">
                    Dock Door
                </td>
                <td align="left" height="13" class="style1">

                    <select size="1" name="srt_dock_doors">
                        <option>Select</option>
                        <?php

                                    $gsql = "SELECT * FROM loop_dock_doors_sortwh WHERE srt_warehouse_id ='" . $pa_warehouse . "'";
                                    db();
                                    $gresult = db_query($gsql);
                                    while ($gmyrowsel = array_shift($gresult)) {
                                    ?>
                        <option <?php if ($_REQUEST["dockdooredit"] == "true") {
                                                    if ($gmyrowsel["dock_doors_names"] == $_REQUEST["srt_dock_doors"]) {
                                                        echo "selected";
                                                    }
                                                } ?> value="<?php echo $gmyrowsel["dock_doors_names"]; ?>">
                            <?php echo $gmyrowsel["dock_doors_names"]; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width: 205px" class="style1">
                    Note
                </td>
                <td align="left" height="13" class="style1">
                    <?php if ($_REQUEST["dockdooredit"] == "true") {
                                ?>
                    <input type="text" value="<?php echo $srt_ucbdockdoor_note; ?>" id="srt_ucbdockdoor_note"
                        name="srt_ucbdockdoor_note">
                    <?php
                                } else {
                                ?>
                    <input type="text" value="" id="srt_ucbdockdoor_note" name="srt_ucbdockdoor_note">
                    <?php
                                }
                                ?>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan="2" class="style1">
                    <input type="submit" value="Submit" id="updatedockdoor" name="updatedockdoor">
                    &nbsp;&nbsp;&nbsp;
                    <?php if ($_REQUEST["dockdooredit"] == "true") {
                                ?>
                    <a href="javascript: window.history.go(-1)">Ignore</a>
                    <?php
                                }
                                ?>

                </td>
            </tr>
        </table>
    </form>
    <?php
            } else {
            ?>
    <table cellSpacing="1" cellPadding="1" border="0" style="width: 600px">
        <tr align="middle">
            <td bgColor="#99FF99" colspan="2">
                <font size="1">PLACED IN UCB DOCK DOOR - <a
                        href="viewCompany_func1_transaction.php?ID=<?php echo $_REQUEST['ID']; ?>&show=transactions&warehouse_id=<?php echo isset($id); ?>&rec_id=<?php echo $rec_id; ?>&rec_type=<?php echo isset($rec_type); ?>&proc=View&display=seller_sort&dockdooredit=true">EDIT</a>
                </font>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Dock Door
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $srt_dock_doors; ?>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Note
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $srt_ucbdockdoor_note; ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Employee
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $srt_ucbdockdoor_by; ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Date/Time
            </td>
            <td align="left" height="13" class="style1">
                <?php echo date("m/d/Y H:i:s", strtotime($srt_ucbdockdoor_dt)) . " CT"; ?>
            </td>
        </tr>
    </table>
    <?php
            }
            echo "<br/>";
        }
    }
    ?>

    <!--End place in UCB dockdoor table-->

    <?php

    if ($pa_warehouse != 238) {
        if ($pr_recycling == 0) {

            $print_sort_rept_flg = $trailer_view_row["manulasortrep_print"];
            $print_sort_rept_empby = $trailer_view_row["manulasortrep_printby"];
            $print_sort_rept_ondt = $trailer_view_row["manulasortrep_printtime"];
            if ($print_sort_rept_flg == "") {
    ?>
    <form action="print_sort_rept_save.php" id="print_sort_rept_frm" name="print_sort_rept_frm" method="post">
        <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
        <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
        <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
        <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
        <input type="hidden" name="sortype" value="printstatus" />
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 600px">
            <tr align="middle">
                <td bgColor="#FB8A8A" colspan="2">
                    <font size="1">PRINT SORT REPORT</font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width:205px;" class="style1">
                    Sort Report
                </td>
                <td align="left" height="13" class="style1">
                    <a href="sortreport2.php?rec_id=<?php echo $_REQUEST["rec_id"] ?>" target="_blank">View Sort
                        Report</a>

                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan="2" class="style1">
                    <input type="submit" name="Submit" value="UPDATE PRINT STATUS" />
                    &emsp;&emsp;
                </td>
            </tr>
        </table>
    </form>
    <?php

            } else {
            ?>
    <form action="print_sort_rept_save.php" id="print_sort_rept_frm" name="print_sort_rept_frm" method="post">
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 600px">
            <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
            <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
            <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
            <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
            <input type="hidden" name="sortype" value="undo" />
            <tr align="middle">
                <td bgColor="#99FF99" colspan="2">
                    <font size="1">PRINT SORT REPORT</font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width:205px;" class="style1">
                    Sort Report
                </td>
                <td align="left" height="13" class="style1">
                    <a href="sortreport2.php?rec_id=<?php echo $_REQUEST["rec_id"] ?>" target="_blank">View Sort
                        Report</a>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="left" height="13" class="style1">
                    Employee
                </td>
                <td align="left" height="13" class="style1">
                    <?php echo $print_sort_rept_empby; ?>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" class="style1">
                    Date/Time
                </td>
                <td align="left" height="13" class="style1">
                    <?php echo $print_sort_rept_ondt; ?>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan="2" class="style1">
                    <input type="submit" name="Submit" value="UNDO" />
                    &emsp;&emsp;
                </td>
            </tr>
        </table>
    </form>
    <?php
            }
            echo "<br/>";
        }
    }
    ?>


    <!--unloaded inside warehouse-->
    <?php

    if ($pa_warehouse != 238) {
        if ($pr_recycling == 0) {

            $unloadededit = $_REQUEST["unloadededit"];
            if ($ucbunloaded_flg == 0 || $unloadededit == "true") {
    ?>
    <form METHOD="POST" action="ucbunloaded_save.php">
        <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
        <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
        <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
        <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />

        <input type="hidden" name="rec_id_unloaded" value="<?php echo $_REQUEST["rec_id"]; ?>">
        <input type="hidden" name="unloadednote_wid" value="<?php echo $_REQUEST["id"]; ?>">
        <input type="hidden" name="unloadednoteflg" value="yes">
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 600px">
            <tr align="middle">
                <?php
                            if ($_REQUEST['unloadededit'] == "true") {
                                $setbgcolor = "#FFF1CB";
                            } else {
                                $setbgcolor = "#FB8A8A";
                            }
                            ?>
                <td bgColor="<?php echo $setbgcolor ?>" colspan="2">
                    <font size="1">UNLOADED INSIDE WAREHOUSE </font>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width: 205px" class="style1">
                    Area Unloaded
                </td>
                <td align="left" height="13" class="style1">
                    <?php if ($_REQUEST["unloadededit"] == "true") {
                                ?>
                    <input type="text" value="<?php echo $area_unloaded; ?>" id="area_unloaded" name="area_unloaded">
                    <?php
                                } else {
                                ?>
                    <input type="text" value="" id="area_unloaded" name="area_unloaded">
                    <?php
                                }
                                ?>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width: 205px" class="style1">
                    Note
                </td>
                <td align="left" height="13" class="style1">
                    <?php if ($_REQUEST["unloadededit"] == "true") {
                                ?>
                    <input type="text" value="<?php echo $ucbunloaded_note; ?>" id="ucbunloaded_note"
                        name="ucbunloaded_note">
                    <?php
                                } else {
                                ?>
                    <input type="text" value="" id="ucbunloaded_note" name="ucbunloaded_note">
                    <?php
                                }
                                ?>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan="2" style="width: 205px" class="style1">
                    <input type="submit" value="Submit" id="updateunloaded" name="updateunloaded">
                    &nbsp;&nbsp;&nbsp;
                    <?php if ($_REQUEST["unloadededit"] == "true") {
                                ?>
                    <a href="javascript: window.history.go(-1)">Ignore</a>
                    <?php
                                }
                                ?>

                </td>
            </tr>
        </table>
    </form>
    <?php
            } else {
            ?>
    <table cellSpacing="1" cellPadding="1" border="0" style="width: 600px">
        <tr align="middle">
            <td bgColor="#99FF99" colspan="2">
                <font size="1">UNLOADED INSIDE WAREHOUSE - <a
                        href="viewCompany_func1_transaction.php?ID=<?php echo $_REQUEST['ID']; ?>&show=transactions&warehouse_id=<?php echo isset($id); ?>&rec_id=<?php echo $rec_id; ?>&rec_type=<?php echo isset($rec_type); ?>&proc=View&display=seller_sort&unloadededit=true">EDIT</a>
                </font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Area Unloaded:
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $area_unloaded; ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Note
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $ucbunloaded_note; ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Employee
            </td>
            <td align="left" height="13" class="style1">
                <?php echo $ucbunloaded_by; ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="left" height="13" style="width: 205px" class="style1">
                Date/Time
            </td>
            <td align="left" height="13" class="style1">
                <?php echo date("m/d/Y H:i:s", strtotime($ucbunloaded_dt)) . " CT"; ?>
            </td>
        </tr>
    </table>
    <?php
            }
            echo "<br/>";
        }
    }

    ?>
    <br>
    <!--End unloaded inside warehouse-->



    <?php

    $dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "' AND sort_entered = 1";
    db();
    $dt_view_res = db_query($dt_view_qry);
    //echo $dt_view_qry;
    $num_rows = tep_db_num_rows($dt_view_res);
    //
    $dt_view_qry1 = "SELECT * from loop_boxes_sort WHERE trans_rec_id = '" . $rec_id . "'";
    //echo $dt_view_qry;
    db();
    $dt_view_res1 = db_query($dt_view_qry1);
    $num_rows_srt = tep_db_num_rows($dt_view_res1);
    //
    if (($num_rows < 1) || ($_GET["pa_edit"] == "true") || ($num_rows_srt == 0)) {
        //echo $dt_view_qry;
    ?>

    <script LANGUAGE="JavaScript">
    function onsubmitform() {
        var flg = "yes";
        if (document.getElementById("pickuparrg_flg").value == "yes") {
            var newflg = confirm(
                "In the pickup arrangement section warehouse details not updated, Do you still wish to add sort report?"
            );
            if (newflg == true) {
                flg = "yes";
            } else {
                flg = "no";
                return false;
            }
        }

        if (flg == "yes") {
            if (document.getElementById("txtemployee").value == "") {
                alert("Please enter the user initials.")
                return false;
            } else {
                document.frmsort.action = "addboxsort_mrg.php";
                document.getElementById("btnaddinv").style.display = "none";
                document.getElementById("sort_add_msg").innerHTML =
                    "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
                return true;
            }
        }
    }
    </script>

    <!-- INITIAL SORT -->
    <form name="frmsort" method="post" action="#" encType="multipart/form-data" onsubmit="return onsubmitform();">
        <input type="hidden" name="ID" value="<?php echo $_REQUEST['ID']; ?>" />
        <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
        <input type="hidden" name="rec_type" value="<?php echo isset($rec_type); ?>" />
        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
        <input type="hidden" name="rec_id" value="<?php echo $rec_id; ?>" />
        <input type="hidden" name="update" value="no" />
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 644px" id="table4">
            <tr align="middle">
                <td bgColor="#fb8a8a" colspan="12">
                    <font size="1">SORT REPORT - TRAILER #<?php echo $trailer_number; ?></font>
                </td>
            </tr>
            <?php
                $dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "'";
                db();
                $dt_view_res = db_query($dt_view_qry);
                $firstrec_flg = "yes";
                while ($dt_view_row = array_shift($dt_view_res)) {

                    if ($dt_view_row["pa_warehouse"] == '' && $firstrec_flg == "yes") { ?>
            <input type="hidden" name="pickuparrg_flg" id="pickuparrg_flg" value="yes" />
            <tr align="middle">
                <td bgColor="#fb8a8a" colSpan="12">
                    <font size="1">WAREHOUSE NAME: </font>
                </td>
            </tr>
            <?php $firstrec_flg = "no";
                    } else {

                        $dt_view_qry_tmp = "SELECT company_name from loop_warehouse where id = '" . $dt_view_row["pa_warehouse"] . "'";
                        db();
                        $dt_view_res_tmp = db_query($dt_view_qry_tmp);
                        $tmp_warenm = "";
                        while ($dt_view_row_tmp = array_shift($dt_view_res_tmp)) {
                            $tmp_warenm = $dt_view_row_tmp["company_name"];
                        }
                    ?>
            <input type="hidden" name="pickuparrg_flg" id="pickuparrg_flg" value="no" />
            <tr align="middle">
                <td bgColor="#fb8a8a" colSpan="12">
                    <font size="1">WAREHOUSE NAME:<?php echo $tmp_warenm; ?></font>
                </td>
            </tr>

            <?php $firstrec_flg = "no";
                    }

                    if ($dt_view_row["pa_warehouse"] != 'No Delivery Warehouse') {
                    ?>
            <input type="hidden" name="sort_warehouse" value="<?php echo $dt_view_row["pa_warehouse"]; ?>" />
            <?php
                    } else {
                    ?>
            <tr bgColor="#e4e4e4">
                <td colspan="2" height="13" class="style1" align="left">
                    <Font Face='arial' size='2'>
                        <select size="1" name="sort_warehouse">
                            <option value="">Please Select</option>
                            <?php

                                        $gsql = "SELECT * FROM loop_warehouse WHERE rec_type = 'Sorting'";
                                        db();
                                        $gresult = db_query($gsql);
                                        while ($gmyrowsel = array_shift($gresult)) {
                                            echo '<option value="' . $gmyrowsel["id"] . '">' . $gmyrowsel["warehouse_name"] . '</option>';
                                        }
                                        ?>
                        </select>
                    </font>&nbsp;
                </td>
                <td align="left" height="13" style="width: 578px" class="style1">
                    <Font size='2'>
                        Please Select Warehosue</font>
                </td>
            </tr>
            <?php
                    }
                }
                ?>
            <tr bgColor="#e4e4e4">
                <td height="13" class="style1" align="center">ID</td>
                <td height="13" class="style1" align="center">TYPE</td>
                <td height="13" class="style1" align="center">VENDOR</td>
                <td height="13" class="style1" align="center">BOX NAME</td>
                <td height="13" class="style1" align="center">DESCRIPTION</td>
                <td height="13" class="style1" align="center">BOXES<br />per<br>PALLET</td>
                <td height="13" class="style1" align="center">x</td>
                <td height="13" class="style1" align="center"># of<br>COMPLETED<br>PALLETS</td>
                <td height="13" class="style1" align="center">
                    GOOD</td>
                <td height="13" class="style1" align="center">
                    BAD</td>
                <td height="13" class="style1" align="center">
                    GOOD VALUE</td>
                <td height="13" class="style1" align="center">
                    BAD VALUE</td>
                <td height="13" class="style1" align="center">
                    AVAILABLE</td>
            </tr>
            <?php
                //echo "<br>"."SELECT * FROM loop_boxes_to_warehouse INNER JOIN loop_boxes ON loop_boxes_to_warehouse.loop_boxes_id = loop_boxes.id WHERE loop_boxes_to_warehouse.loop_warehouse_id = " . $_REQUEST["warehouse_id"] . " ORDER BY loop_boxes.bdescription ASC";

                db();
                $get_boxes_query = db_query("SELECT * FROM loop_boxes_to_warehouse INNER JOIN loop_boxes ON loop_boxes_to_warehouse.loop_boxes_id = loop_boxes.id WHERE loop_boxes_to_warehouse.loop_warehouse_id = " . $_REQUEST["warehouse_id"] . " ORDER BY loop_boxes.bdescription ASC");
                $i = 0;
                $flg_alternate = 1;
                //echo "<pre>"; print_r($get_boxes_query[0]); echo "</pre>";
                $count1 = tep_db_num_rows($get_boxes_query);
                while ($boxes = array_shift($get_boxes_query)) {
                    //$count=tep_db_num_rows($get_boxes_query);
                    //$count_and_one = $count + 1;
                    //$i++;
                    $box_type = "";
                    $box_type_sort = 0;
                    if ($boxes["type"] == "Gaylord" || $boxes["type"] == "GaylordUCB" || $boxes["type"] == "Loop" || $boxes["type"] == "PresoldGaylord") {
                        $box_type = "Gaylord Tote";
                        $box_type_sort = 1;
                    }
                    if (
                        $boxes["type"] == "LoopShipping" || $boxes["type"] == "Box" || $boxes["type"] == "Boxnonucb" || $boxes["type"] == "Presold"
                        || $boxes["type"] == "Medium" || $boxes["type"] == "Large" || $boxes["type"] == "Xlarge"
                    ) {
                        $box_type = "Shipping Box";
                        $box_type_sort = 2;
                    }
                    if ($boxes["type"] == "SupersackUCB" || $boxes["type"] == "SupersacknonUCB") {
                        $box_type = "SuperSack";
                        $box_type_sort = 3;
                    }
                    if ($boxes["type"] == "PalletsUCB" || $boxes["type"] == "PalletsnonUCB") {
                        $box_type = "Pallets";
                        $box_type_sort = 4;
                    }
                    if ($boxes["type"] == "DrumBarrelUCB" || $boxes["type"] == "DrumBarrelnonUCB") {
                        $box_type = "Drum Barrel";
                        $box_type_sort = 5;
                    }
                    if ($boxes["type"] == "Recycling") {
                        $box_type = "Recycling";
                        $box_type_sort = 6;
                    }
                    if ($boxes["type"] == "Other") {
                        $box_type = "Other";
                        $box_type_sort = 7;
                    }

                    if ($boxes["vendor_b2b_rescue"] != "") {
                        $q1 = "SELECT * FROM loop_warehouse where id = " . $boxes["vendor_b2b_rescue"];
                        db();
                        $v_query = db_query($q1);
                        while ($v_fetch = array_shift($v_query)) {
                            $vendor_nm = $v_fetch['company_name'];
                        }
                    } else {
                        $vendor_nm = $boxes["source"];
                        if (strlen($boxes["source"]) > 12) {
                            $vendor_nm = substr($boxes["source"], 0, 25) . "...";
                        }
                    }

                    $nickname = $boxes["nickname"];
                    if ($nickname == "") {
                        $nickname = $boxes["bdescription"];
                    }

                    $MG_sort_array[] = array(
                        'loop_boxes_id' => $boxes["loop_boxes_id"], 'b2b_id' => $boxes["b2b_id"], 'id' => $boxes["id"], 'box_type_sort' => $box_type_sort,
                        'box_type' => $box_type, 'vendor_nm' => $vendor_nm, 'nickname' => $nickname,
                        'boxgood' => $boxes["boxgood"],    'boxbad' => $boxes["boxbad"],
                        'boxgoodvalue' => $boxes["boxgoodvalue"], 'boxbadvalue' => $boxes["boxbadvalue"],
                        'system_description' => $boxes["system_description"], 'stack_per_pallet' => $boxes["stack_per_pallet"], 'bpallet_qty' => $boxes["bpallet_qty"]
                    );
                }

                $MGarray = $MG_sort_array;
                $MGArraysort_I = array();
                $MGArraysort_II = array();

                foreach ($MGarray as $MGArraytmp) {
                    $MGArraysort_I[] = $MGArraytmp['box_type_sort'];
                    $MGArraysort_II[] = $MGArraytmp['nickname'];
                }
                array_multisort($MGArraysort_I, SORT_ASC, $MGArraysort_II, SORT_ASC, $MGarray);


                foreach ($MGarray as $rw1) {
                    if ($flg_alternate == 1) {
                        $tr_color_css = "#e4e4e4";
                        $flg_alternate = 0;
                    } else {
                        $tr_color_css = "#fbfbfb";
                        $flg_alternate = 1;
                    }

                ?>
            <tr bgColor="<?php echo $tr_color_css; ?>">
                <td align="center" height="13" style="width:94px;" class="style1">
                    <font size="1" Face="arial">
                        <a target="_blank"
                            href='manage_box_b2bloop.php?id=<?php echo $rw1["loop_boxes_id"]; ?>&proc=View'><?php echo $rw1["b2b_id"]; ?></a>
                    </font>
                </td>
                <td>
                    <font size="1" Face="arial"><?php echo $rw1["box_type"]; ?></font>
                </td>
                <td>
                    <font size="1" Face="arial"><?php echo $rw1["vendor_nm"]; ?></font>
                </td>
                <td align="center" height="13" style="width:94px;" class="style1">
                    <font size="1" Face="arial"><?php echo $rw1["nickname"]; ?></font>
                </td>
                <td align="left" height="13" style="width:578px;" class="style1">
                    <font size="1" Face="arial"><?php echo $rw1["system_description"]; ?></font>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td height="13" class="style1" align="right">
                    <input type="hidden" name="box_id[]" value="<?php echo $rw1["loop_boxes_id"]; ?>" />
                    <input size="3" name="boxgood[]" type=text>
                    <?php $boxgood = isset($boxgood) + $rw1["boxgood"]; ?>
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="boxbad[]" type=text>
                    <?php $boxbad = isset($boxbad) + $rw1["boxbad"]; ?>
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="boxgoodvalue[]" type=text value="<?php echo $rw1["boxgoodvalue"]; ?>">
                    <?php $boxgoodval = isset($boxgoodval) + $rw1["boxgoodvalue"]; ?>
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="boxbadvalue[]" type=text value="<?php echo $rw1["boxbadvalue"]; ?>">
                    <?php $boxbadval = isset($boxbadval) + $rw1["boxbadvalue"]; ?>
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="quantity_available[]" type=text
                        value="<?php echo $rw1["quantity_available"]; ?>">
                    <?php $boxavailableval = isset($boxavailableval) + $rw1["quantity_available"]; ?>
                </td>

            </tr>
            <?php } ?>

            <tr bgColor="<?php echo $tr_color_css; ?>">
                <td align="center" height="13" style="width:94px;" class="style1">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="center" height="13" style="width:94px;" class="style1">&nbsp;</td>
                <td align="left" height="13" style="width:578px;" class="style1">
                    <font size="1" Face="arial">
                        Scrap
                    </font>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td height="13" class="style1" align="right">
                    <input size="3" type="text" name="boxscrap">
                </td>
                <td height="13" class="style1" align="right">&nbsp;
                </td>
                <td height="13" class="style1" align="right">&nbsp;
                </td>
                <td height="13" class="style1" align="right">&nbsp;
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td colspan="8" height="13" class="style1" align="right">Total</td>
                <td height="13" class="style1"><input size="3" type="text" name="boxscrap1"
                        value="<?php echo isset($boxgood); ?>"></td>
                <td height="13" class="style1"><input size="3" type="text" name="boxscrap2"
                        value="<?php echo isset($boxbad); ?>"></td>
                <td height="13" class="style1"><input size="3" type="text" name="boxscrap3"
                        value="<?php echo isset($boxgoodval); ?>"></td>
                <td height="13" class="style1"><input size="3" type="text" name="boxscrap4"
                        value="<?php echo isset($boxbadval); ?>"></td>
                <td height="13" class="style1"><input size="3" type="text" name="boxscrap4"
                        value="<?php echo isset($boxavailableval); ?>"></td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td colspan="3" height="13" class="style12right">Frieght</td>
                <td colspan="9" height="13" class="style1">
                    <input size="30" type="text" name="freightcharge">
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td colspan="3" height="13" class="style12right">Other Charges</td>
                <td colspan="9" height="13" class="style1">
                    <input size="30" type="text" name="othercharge">
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td colspan="3" height="13" class="style12right">Other Details</td>
                <td colspan="9" height="13" class="style1">
                    <input size="30" type="text" name="otherdetails">
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td colspan="3" height="13" class="style12right">
                    User Initials
                </td>
                <td height="13" colspan="9" class="style1" align="left">
                    <input size="10" type="text" name="txtemployee" id="txtemployee">
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td height="13" colspan="3" class="style12right">
                    Notes</td>
                <td height="13" colspan="9" class="style1">
                    <Font size='2' Face="arial">
                        <p align="left"><textarea rows="3" cols="30" name="boxnotes"></textarea>
                    </font>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td colspan="12" align="left" height="19" class="style1">
                    <p align="center">
                        <input type="hidden" name="count" value="<?php echo $count1; ?>">
                        <input type="submit" name="btnaddinv" id="btnaddinv" value="Submit  &amp; Add to Inventory"
                            style="cursor:pointer;">
                    <div name="sort_add_msg" id="sort_add_msg"></div>
                </td>
            </tr>
        </table>
    </form>
    <?php } ?>

    <!-- EDIT A SORT -->
    <?php if ($_GET["sort_edit"] == "true") { ?>

    <script LANGUAGE="JavaScript">
    function onsubmitformedit() {
        var flg = "yes";
        if (document.getElementById("pickuparrg_flg").value == "yes") {
            var newflg = confirm(
                "In the pickup arrangement section warehouse details not updated, Do you still wish to add sort report?"
            );
            if (newflg == true) {
                flg = "yes";
            } else {
                flg = "no";
                return false;
            }
        }

        if (flg == "yes") {
            if (document.getElementById("txtemployee").value == "") {
                alert("Please enter the user initials.")
                return false;
            } else {
                document.frmsortedit.action = "addboxsort_mrg.php";
                document.getElementById("btnaddinvedit").style.display = "none";
                document.getElementById("sort_add_msg_edit").innerHTML =
                    "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
                return true;
            }
        }
    }
    </script>



    <form name="frmsortedit" method="post" action="#" encType="multipart/form-data"
        onsubmit="return onsubmitformedit();">
        <input type="hidden" name="ID" value="<?php echo $_REQUEST['ID']; ?>">
        <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
        <input type="hidden" name="rec_type" value="<?php echo isset($rec_type); ?>" />
        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
        <input type="hidden" name="rec_id" value="<?php echo $rec_id; ?>" />
        <input type="hidden" name="update" value="yes" />
        <input type="hidden" name="updatecrm" value="yes" />
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 644px" id="table4">
            <tr align="middle">
                <td bgColor="#dfdf78" colSpan="12">
                    <font size="1">SORT REPORT - TRAILER #<?php echo $trailer_number; ?></font>
                </td>
            </tr>
            <?php

                $dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "'";
                db();
                $dt_view_res = db_query($dt_view_qry);
                while ($dt_view_row = array_shift($dt_view_res)) {


                    if ($dt_view_row["pa_warehouse"] == '' && $firstrec_flg == "yes") { ?>
            <input type="hidden" name="pickuparrg_flg" id="pickuparrg_flg" value="yes" />
            <tr align="middle">
                <td bgColor="#dfdf78" colSpan="12">
                    <font size="1">WAREHOUSE NAME: </font>
                </td>
            </tr>
            <?php $firstrec_flg = "no";
                    } else {

                        $dt_view_qry_tmp = "SELECT company_name from loop_warehouse where id = '" . $dt_view_row["pa_warehouse"] . "'";
                        db();
                        $dt_view_res_tmp = db_query($dt_view_qry_tmp);
                        $tmp_warenm = "";
                        while ($dt_view_row_tmp = array_shift($dt_view_res_tmp)) {
                            $tmp_warenm = $dt_view_row_tmp["company_name"];
                        }
                    ?>
            <input type="hidden" name="pickuparrg_flg" id="pickuparrg_flg" value="no" />
            <tr align="middle">
                <td bgColor="#dfdf78" colSpan="12">
                    <font size="1">WAREHOUSE NAME: <?php echo $tmp_warenm; ?></font>
                </td>
            </tr>
            <?php $firstrec_flg = "no";
                    }

                    if ($dt_view_row["pa_warehouse"] != 'No Delivery Warehouse') {
                        $gsql = "SELECT * FROM loop_warehouse WHERE id = '" . $dt_view_row["pa_warehouse"] . "'";
                        db();
                        $gresult = db_query($gsql);
                        while ($gmyrowsel = array_shift($gresult)) {
                        ?>
            <input type="hidden" name="sort_warehouse" value="<?php echo $gmyrowsel["id"]; ?>" />

            <?php }
                    } else {
                        ?>
            <tr bgColor="#dfdf78">
                <td colspan="6" height="13" class="style1" align="left">
                    <Font Face='arial' size='2'>
                        <select size="1" name="sort_warehouse">
                            <option value="">Please Select
                                <?php

                                            $gsql = "SELECT * FROM loop_warehouse WHERE rec_type = 'Sorting'";
                                            db();
                                            $gresult = db_query($gsql);
                                            while ($gmyrowsel = array_shift($gresult)) {
                                            ?>
                            <option value="<?php echo $gmyrowsel["id"]; ?>"><?php echo $gmyrowsel["warehouse_name"]; ?>
                            </option>
                            <?php } ?>
                        </select>&nbsp;
                </td>
                <td colspan="6" align="left" height="13" style="width: 578px" class="style1">
                    Please Select Warehosue</td>
            </tr>
            <?php
                    }
                }
                ?>
            <tr bgColor="#e4e4e4">
                <td height="13" class="style1" align="center">ID</td>
                <td height="13" class="style1" align="center">TYPE</td>
                <td height="13" class="style1" align="center">VENDOR</td>
                <td height="13" class="style1" align="center">BOX NAME</td>
                <td height="13" class="style1" align="center">DESCRIPTION</td>
                <td height="13" class="style1" align="center">BOXES<br />per<br>PALLET</td>
                <td height="13" class="style1" align="center">x</td>
                <td height="13" class="style1" align="center"># of<br>COMPLETED<br>PALLETS</td>
                <td height="13" class="style1" align="center">
                    GOOD</td>
                <td height="13" class="style1" align="center">
                    BAD</td>
                <td height="13" class="style1" align="center">
                    GOOD VALUE</td>
                <td height="13" class="style1" align="center">
                    BAD VALUE</td>
                <td height="13" class="style1" align="center">
                    AVAILABLE</td>

            </tr>
            <?php
                //not saving the blank good or bad value rows
                //$get_boxes_query = db_query("SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $rec_id." ORDER BY loop_boxes_sort.id", db());

                db();
                $get_boxes_query = db_query("SELECT * FROM loop_boxes_to_warehouse INNER JOIN loop_boxes ON loop_boxes_to_warehouse.loop_boxes_id = loop_boxes.id WHERE loop_boxes_to_warehouse.loop_warehouse_id = " . $_REQUEST["warehouse_id"] . " ORDER BY loop_boxes.bdescription ASC");

                $i = 0;
                $flg_alternate = 1;
                $count = tep_db_num_rows($get_boxes_query);
                while ($boxes_to_warehouse = array_shift($get_boxes_query)) {
                    $box_type = "";
                    $box_type_sort = 0;
                    if ($boxes_to_warehouse["type"] == "Gaylord" || $boxes_to_warehouse["type"] == "GaylordUCB" || $boxes_to_warehouse["type"] == "Loop" || $boxes_to_warehouse["type"] == "PresoldGaylord") {
                        $box_type = "Gaylord Tote";
                        $box_type_sort = 1;
                    }
                    if (
                        $boxes_to_warehouse["type"] == "LoopShipping" || $boxes_to_warehouse["type"] == "Box" || $boxes_to_warehouse["type"] == "Boxnonucb" || $boxes_to_warehouse["type"] == "Presold"
                        || $boxes_to_warehouse["type"] == "Medium" || $boxes_to_warehouse["type"] == "Large" || $boxes_to_warehouse["type"] == "Xlarge"
                    ) {
                        $box_type = "Shipping Box";
                        $box_type_sort = 2;
                    }
                    if ($boxes_to_warehouse["type"] == "SupersackUCB" || $boxes_to_warehouse["type"] == "SupersacknonUCB") {
                        $box_type = "SuperSack";
                        $box_type_sort = 3;
                    }
                    if ($boxes_to_warehouse["type"] == "PalletsUCB" || $boxes_to_warehouse["type"] == "PalletsnonUCB") {
                        $box_type = "Pallets";
                        $box_type_sort = 4;
                    }
                    if ($boxes_to_warehouse["type"] == "DrumBarrelUCB" || $boxes_to_warehouse["type"] == "DrumBarrelnonUCB") {
                        $box_type = "Drum Barrel";
                        $box_type_sort = 5;
                    }
                    if ($boxes_to_warehouse["type"] == "Recycling") {
                        $box_type = "Recycling";
                        $box_type_sort = 6;
                    }
                    if ($boxes_to_warehouse["type"] == "Other") {
                        $box_type = "Other";
                        $box_type_sort = 7;
                    }

                    if ($boxes_to_warehouse["vendor_b2b_rescue"] != "") {
                        $q1 = "SELECT * FROM loop_warehouse where id = " . $boxes_to_warehouse["vendor_b2b_rescue"];
                        db();
                        $v_query = db_query($q1);
                        while ($v_fetch = array_shift($v_query)) {
                            $vendor_nm = $v_fetch['company_name'];
                        }
                    } else {
                        $vendor_nm = $boxes_to_warehouse["source"];
                        if (strlen($boxes_to_warehouse["source"]) > 12) {
                            $vendor_nm = substr($boxes_to_warehouse["source"], 0, 25) . "...";
                        }
                    }

                    $nickname = $boxes_to_warehouse["nickname"];
                    if ($nickname == "") {
                        $nickname = $boxes_to_warehouse["bdescription"];
                    }

                    $MG_sort_array[] = array(
                        'loop_boxes_id' => $boxes_to_warehouse["loop_boxes_id"], 'b2b_id' => $boxes_to_warehouse["b2b_id"], 'id' => $boxes_to_warehouse["id"],
                        'box_type_sort' => $box_type_sort, 'box_type' => $box_type, 'vendor_nm' => $vendor_nm,
                        'nickname' => $nickname, 'boxgood' => $boxes_to_warehouse["boxgood"],
                        'boxbad' => $boxes_to_warehouse["boxbad"], 'boxgoodvalue' => $boxes_to_warehouse["boxgoodvalue"],
                        'boxbadvalue' => $boxes_to_warehouse["boxbadvalue"], 'quantity_available' => $boxes_to_warehouse["quantity_available"],
                        'system_description' => $boxes_to_warehouse["system_description"], 'stack_per_pallet' => $boxes_to_warehouse["stack_per_pallet"], 'bpallet_qty' => $boxes_to_warehouse["bpallet_qty"]
                    );
                }

                $MGarray = $MG_sort_array;
                $MGArraysort_I = array();
                $MGArraysort_II = array();

                foreach ($MGarray as $MGArraytmp) {
                    $MGArraysort_I[] = $MGArraytmp['box_type_sort'];
                    $MGArraysort_II[] = $MGArraytmp['nickname'];
                }
                array_multisort($MGArraysort_I, SORT_ASC, $MGArraysort_II, SORT_ASC, $MGarray);


                foreach ($MGarray as $rw2) {

                    $rec_found_1 = "no";
                    db();
                    $get_sort_qry = db_query("SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $rec_id . " and loop_boxes.id = '" . $rw2["id"] . "' ORDER BY loop_boxes_sort.id");
                    while ($boxes = array_shift($get_sort_qry)) {
                        $rec_found_1 = "yes";
                        //$count_and_one = $count + 1;
                        $i++;

                        if ($flg_alternate == 1) {
                            $tr_color_css = "#e4e4e4";
                            $flg_alternate = 0;
                        } else {
                            $tr_color_css = "#fbfbfb";
                            $flg_alternate = 1;
                        }

                ?>
            <tr bgColor="<?php echo $tr_color_css; ?>">
                <td align="center" height="13" style="width:94px;" class="style1">
                    <font size="1" Face="arial">
                        <a target="_blank"
                            href='manage_box_b2bloop.php?id=<?php echo $boxes["loop_boxes_id"]; ?>&proc=View'><?php echo $boxes["b2b_id"]; ?></a>
                    </font>
                </td>
                <td>
                    <font size="1" Face="arial"><?php echo $rw2["box_type"]; ?></font>
                </td>
                <td>
                    <font size="1" Face="arial"><?php echo $rw2["vendor_nm"]; ?></font>
                </td>
                <td align="center" height="13" style="width:94px;" class="style1">
                    <font size="1" Face="arial">
                        <?php
                                    $nickname = $boxes["nickname"];
                                    if ($nickname == "") {
                                        $nickname = $boxes["bdescription"];
                                    }
                                    echo $nickname;
                                    ?>
                    </font>
                </td>
                <td align="left" height="13" style="width:578px;" class="style1">
                    <font size="1" Face="arial">

                        <?php echo $boxes["system_description"]; ?>
                    </font>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td height="13" class="style1" align="right">
                    <input type="hidden" name="boxgood_old[]" value="<?php echo $boxes["boxgood"]; ?>" />
                    <input type="hidden" name="boxbad_old[]" value="<?php echo $boxes["boxbad"]; ?>" />
                    <input type="hidden" name="boxbad_desc_old[]" value="<?php echo $boxes["bdescription"]; ?>" />
                    <input type="hidden" name="box_row_id[]" value="<?php echo $boxes["id"]; ?>">
                    <input type="hidden" name="box_id[]" value="<?php echo $boxes["box_id"]; ?>" />
                    <input size="3" name="boxgood[]" type="text" value="<?php echo $boxes["boxgood"]; ?>">
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="boxbad[]" type="text" value="<?php echo $boxes["boxbad"]; ?>">
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="boxgoodvalue[]" type="text"
                        value="<?php echo $boxes["sort_boxgoodvalue"]; ?>">
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="boxbadvalue[]" type="text" value="<?php echo $boxes["sort_boxbadvalue"]; ?>">
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="quantity_available[]" type="text"
                        value="<?php echo $boxes["quantity_available"]; ?>">
                </td>

            </tr>
            <?php

                        $box_old_scrap = $boxes["boxscrap"];
                        $box_old_notes = $boxes["boxnotes"];
                        $box_old_sort_date = $boxes["sort_date"];
                        $box_old_employee = $boxes["employee"];
                    }

                    if ($rec_found_1 == "no") {
                        $i++;

                        if ($flg_alternate == 1) {
                            $tr_color_css = "#e4e4e4";
                            $flg_alternate = 0;
                        } else {
                            $tr_color_css = "#fbfbfb";
                            $flg_alternate = 1;
                        }

                    ?>
            <tr bgColor="<?php echo $tr_color_css; ?>">
                <td align="center" height="13" class="style1">
                    <font size="1" Face="arial">
                        <a target="_blank"
                            href='manage_box_b2bloop.php?id=<?php echo $rw2["loop_boxes_id"]; ?>&proc=View'><?php echo $rw2["b2b_id"]; ?></a>
                    </font>
                </td>
                <td height="13" class="style1" align="right"><?php echo $rw2["box_type"]; ?></td>
                <td>
                    <font size="1" Face="arial"><?php echo $rw2["vendor_nm"]; ?></font>
                </td>
                <td align="center" height="13" style="width:94px;" class="style1">
                    <font size="1" Face="arial"><?php echo $rw2["nickname"]; ?></font>
                </td>
                <td align="left" height="13" style="width:578px;" class="style1">
                    <font size="1" Face="arial"><?php echo $rw2["system_description"]; ?></font>
                </td>
                <td height="13" class="style1" align="right"></td>
                <td height="13" class="style1" align="right"></td>
                <td height="13" class="style1" align="right"></td>

                <td height="13" class="style1" align="right">
                    <input type="hidden" name="boxgood_old[]" value="0" />
                    <input type="hidden" name="boxbad_old[]" value="0" />
                    <input type="hidden" name="boxbad_desc_old[]" value="" />
                    <input type="hidden" name="box_row_id[]" value="0">
                    <input type="hidden" name="box_id[]" value="<?php echo $rw2["loop_boxes_id"]; ?>" />
                    <input size="3" name="boxgood[]" type="text" value="">
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="boxbad[]" type="text" value="">
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="boxgoodvalue[]" type="text" value="<?php echo $rw2["boxgoodvalue"]; ?>">
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="boxbadvalue[]" type="text" value="<?php echo $rw2["boxbadvalue"]; ?>">
                </td>
                <td height="13" class="style1" align="right">
                    <input size="3" name="quantity_available[]" type="text"
                        value="<?php echo $rw2["quantity_available"]; ?>">
                </td>

            </tr>
            <?php
                    }
                } ?>

            <tr bgColor="<?php echo $tr_color_css; ?>">
                <td align="center" height="13" class="style1">&nbsp;
                </td>
                <td height="13" class="style1" align="right">&nbsp;</td>
                <td height="13" class="style1" align="right">&nbsp;</td>
                <td align="center" height="13" class="style1">&nbsp;
                </td>
                <td align="left" height="13" class="style1">
                    <font size="1" Face="arial">
                        Scrap
                    </font>
                </td>
                <td height="13" class="style1" align="right">&nbsp;</td>
                <td height="13" class="style1" align="right">&nbsp;</td>
                <td height="13" class="style1" align="right">&nbsp;</td>
                <td height="13" class="style1" align="right">
                    <input size="3" type="text" name="boxscrap" value="<?php echo isset($box_old_scrap); ?>">
                </td>
                <td height="13" class="style1" align="right">&nbsp;
                </td>
                <td height="13" class="style1" align="right">&nbsp;
                </td>
                <td height="13" class="style1" align="right">&nbsp;
                </td>
            </tr>
            <?php

                db();
                $dt_view_tran_qry = "SELECT * from loop_transaction WHERE id = " . $rec_id;
                $dt_view_tran = db_query($dt_view_tran_qry);
                $dt_view_tran_row = array_shift($dt_view_tran);
                ?>
            <tr bgColor="#e4e4e4">
                <td colspan="3" height="13" class="style12right">Freight

                </td>
                <td colspan="9" height="13" class="style1">
                    <input size="30" type="text" name="freightcharge"
                        value="<?php echo $dt_view_tran_row["freightcharge"]; ?>">
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td colspan="3" height="13" class="style12right">
                    Other Charges
                </td>
                <td colspan="9" height="13" class="style1">
                    <input size="30" type="text" name="othercharge"
                        value="<?php echo $dt_view_tran_row["othercharge"]; ?>">
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td colspan="3" height="13" class="style12right">
                    Other Details
                </td>
                <td colspan="9" height="13" class="style1">
                    <input size="30" type="text" name="otherdetails"
                        value="<?php echo $dt_view_tran_row["otherdetails"]; ?>">
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td colspan="3" height="13" class="style12right">
                    User Initials
                </td>
                <td height="13" colspan="9" class="style1">
                    <input size="30" type="text" name="txtemployee" id="txtemployee"
                        value="<?php echo isset($box_old_employee); ?>">
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td colspan="3" height="13" class="style12right">
                    Notes</td>
                <td colspan="9" height="13" class="style1">
                    <Font size='2' Face="arial">
                        <p align="left"><textarea rows="3" cols="30"
                                name="boxnotes"><?php echo isset($box_old_notes); ?></textarea>
                    </font>
                </td>
            </tr>
</font>
</font>
<tr bgColor="#e4e4e4">
    <td colspan="12" align="left" height="19" class="style1">
        <p align="center">
            <input type="hidden" name="sort_date_old" value="<?php echo isset($box_old_sort_date); ?>">
            <input type="hidden" name="scrap_old" value="<?php echo isset($box_old_scrap); ?>">
            <input type="hidden" name="notes_old" value="<?php echo isset($box_old_notes); ?>">
            <input type="hidden" name="employee_old" value="<?php echo isset($box_old_employee); ?>">
            <input type="hidden" name="count" value="<?php echo $count; ?>">

            <input style="cursor:pointer;" name="btnaddinvedit" id="btnaddinvedit" type=submit
                value="Submit  &amp; Add to Inventory">
            <font size="1" Face="arial"><a href="javascript: window.history.go(-1)">Ignore</a>
                <div id="sort_add_msg_edit" name="sort_add_msg_edit"></div>
    </td>

</tr>
</table>
</form>
<?php } ?>

<br>

<?php
$good = 0;
$bad = 0; // Added by Mooneem on Apr-24-12 $goodvalue = 0;$badvalue = 0;// Added by Mooneem on Apr-24-12
$dt_view_qry = "SELECT * from loop_boxes_sort WHERE trans_rec_id = '" . $rec_id . "' ORDER BY id";
//echo "<br />".$dt_view_qry;
db();
$dt_view_res = db_query($dt_view_qry);
$num_rows = tep_db_num_rows($dt_view_res);
if ($num_rows > 0) {
?>
<div id='div_show_history' class="white_content"> </div>
<!-- VIEW ENTERED SORT -->
<table cellSpacing="1" cellPadding="1" border="0" style="width: 644px" id="table4">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="12">
            <font size="1">SORT REPORT DATA - TRAILER #<?php echo $trailer_number; ?></font>
            <a
                href="viewCompany_func1_transaction.php?ID=<?php echo $_REQUEST['ID'] ?>&show=transactions&warehouse_id=<?php echo isset($id); ?>&rec_id=<?php echo $rec_id; ?>&rec_type=<?php echo isset($rec_type); ?>&proc=View&searchcrit=&display=seller_sort&sort_edit=true">EDIT</a>
            <a
                href="search_result_include_seller_sort_delete_mrg.php?ID=<?php echo $_REQUEST['ID'] ?>&show=transactions&warehouse_id=<?php echo isset($id); ?>&trans_rec_id=<?php echo $rec_id; ?>">DELETE</a>
            <?php
                $dt_view_qry_ch = "SELECT trans_rec_id FROM history_loop_boxes_sort WHERE trans_rec_id = '" . $rec_id . "' limit 1";
                db();
                $dt_view_res_ch = db_query($dt_view_qry_ch);
                $num_rows_ch = tep_db_num_rows($dt_view_res_ch);
                if ($num_rows_ch > 0) {
                ?>
            <a href="search_result_include_seller_sort_show_history_mrg.php?ID=<?php echo $_REQUEST['ID'] ?>&show=transactions&warehouse_id=<?php echo isset($id); ?>&trans_rec_id=<?php echo $rec_id; ?>&trailer_number=<?php echo $trailer_number ?>"
                target="_blank">SHOW HISTORY</a>
            <!-- <input type="button" id="showHistory" onclick="showTransactionHistory(<?php echo $_REQUEST['ID'] ?>,<?php echo $rec_id; ?>)" value="SHOW HISTORY"> -->
            <?php } ?>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td height="13" class="style1" align="center">ID</td>
        <td height="13" class="style1" align="center">TYPE</td>
        <td height="13" class="style1" align="center">VENDOR</td>
        <td height="13" class="style1" align="center">BOX NAME</td>
        <td height="13" class="style1" align="center">DESCRIPTION</td>
        <td height="13" class="style1" align="center">BOXES<br />per<br>PALLET</td>
        <td height="13" class="style1" align="center">x</td>
        <td height="13" class="style1" align="center"># of<br>COMPLETED<br>PALLETS</td>

        <td height="13" style="width: 84px" class="style1" align="center">
            GOOD
        </td>
        <td height="13" style="width: 94px" class="style1" align="center">
            BAD
        </td>
        <td height="13" style="width: 84px" class="style1" align="center">
            GOOD VALUE
        </td>
        <td height="13" style="width: 94px" class="style1" align="center">
            BAD VALUE
        </td>

    </tr>

    <?php

        $flg_alternate = 1;
        $boxscrap = 0;
        while ($dt_view_row = array_shift($dt_view_res)) {
            if ((!empty($dt_view_row["boxgood"]) && $dt_view_row["boxgood"] > 0) || (!empty($dt_view_row["boxbad"]) && $dt_view_row["boxbad"] > 0)) {

                if ($flg_alternate == 1) {
                    $tr_color_css = "#e4e4e4";
                    $flg_alternate = 0;
                } else {
                    $tr_color_css = "#fbfbfb";
                    $flg_alternate = 1;
                }
        ?>

    <tr bgColor="<?php echo $tr_color_css; ?>">
        <td align="center" height="13" style="width: 94px" class="style1">
            <?php
                        $boxes_bdescription = "";
                        db();
                        $get_boxes_query = db_query("SELECT * FROM loop_boxes WHERE id = '" . $dt_view_row["box_id"] . "'");
                        while ($boxes = array_shift($get_boxes_query)) {
                            $boxes_b2b_id = $boxes["b2b_id"];
                            $boxes_type =  $boxes["type"];
                            $boxes_bdescription = $boxes["bdescription"];
                            $boxes_systemdes = $boxes["system_description"];

                            $q12 = "SELECT * FROM loop_warehouse where id = " . $boxes["vendor_b2b_rescue"];
                            db();
                            $v_query = db_query($q12);
                            while ($v_fetch = array_shift($v_query)) {
                                $supplier_id = $v_fetch["b2bid"];
                                //$vender_nm = get_nickname_val($v_fetch['company_name'], $v_fetch["b2bid"]);
                                $vender_nm = $v_fetch['company_name'];
                            }
                        }
                        ?>
            <a target="_blank"
                href='manage_box_b2bloop.php?id=<?php echo $dt_view_row["box_id"]; ?>&proc=View'><?php echo isset($boxes_b2b_id); ?></a>
        </td>
        <td align="left" height="13" style="width: 578px" class="style1">
            <?php
                        echo isset($boxes_type);
                        ?>
        </td>
        <td align="center" height="13" style="width:94px;" class="style1">
            <font size="1" Face="arial">
                <?php
                            echo isset($vender_nm);
                            ?>
            </font>
        </td>

        <td align="left" height="13" style="width: 578px" class="style1">
            <?php
                        echo $boxes_bdescription;
                        ?>
        </td>
        <td align="left" height="13" style="width: 578px" class="style1">
            <?php
                        echo isset($boxes_systemdes);
                        ?>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td height="13" style="width: 84px" class="style1" align="center">
            <?php echo $dt_view_row["boxgood"];
                        $good = $good + $dt_view_row["boxgood"];
                        $goodvalue = isset($goodvalue) + ($dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"]);
                        ?>
        </td>
        <td height="13" style="width: 94px" class="style1" align="center">
            <?php echo $dt_view_row["boxbad"];
                        $bad = $bad + $dt_view_row["boxbad"];
                        $badvalue = isset($badvalue) + ($dt_view_row["boxbad"] * $dt_view_row["sort_boxbadvalue"]);
                        ?>
        </td>
        <td height="13" style="width: 84px" class="style1" align="center">
            <?php echo $dt_view_row["sort_boxgoodvalue"]; ?>
        </td>
        <td height="13" style="width: 94px" class="style1" align="center">
            <?php echo $dt_view_row["sort_boxbadvalue"];  ?>
        </td>

    </tr>
    <?php
            }
            $boxscrap = $dt_view_row["boxscrap"];
        }
        //For Scrap row

        if ($flg_alternate == 1) {
            $tr_color_css = "#e4e4e4";
            $flg_alternate = 0;
        } else {
            $tr_color_css = "#fbfbfb";
            $flg_alternate = 1;
        }

        ?>
    <tr bgColor="<?php echo $tr_color_css; ?>">
        <td align="center" height="13" style="width: 94px" class="style1">&nbsp;
        </td>
        <td align="left" height="13" style="width: 578px" class="style1">&nbsp;
        </td>
        <td align="center" height="13" style="width:94px;" class="style1">&nbsp;
        </td>

        <td align="left" height="13" style="width: 578px" class="style1">&nbsp;
        </td>
        <td align="left" height="13" style="width: 578px" class="style1">Scrap
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td height="13" style="width: 84px" class="style1" align="center">
            <?php echo $boxscrap;
                $good = $good + $boxscrap;
                ?>
        </td>
        <td height="13" style="width: 94px" class="style1" align="center">&nbsp;
        </td>
        <td height="13" style="width: 84px" class="style1" align="center">&nbsp;
        </td>
        <td height="13" style="width: 94px" class="style1" align="center">&nbsp;
        </td>
    </tr>

    <?php
        $dt_view_qry = "SELECT * from loop_boxes_sort WHERE trans_rec_id = '" . $rec_id . "' LIMIT 0,1";
        db();
        $dt_view_res = db_query($dt_view_qry);
        while ($dt_view_row = array_shift($dt_view_res)) {
        ?>
    <tr bgColor="#e4e4e4">
        <Font size='1' Face='arial'>
            <td colspan="8" height="13" class="style1">TOTALS</td>
            <td height="13" align="center" class="style1"><?php echo $good; ?> </td>
            <td height="13" align="center" class="style1"><?php echo $bad; ?></td>
            <td height="13" align="center" class="style1">&nbsp;</td>
            <td height="13" align="center" class="style1">&nbsp;</td>
        </font>

    </tr>
    <Font size='1'>
        <?php
                $dt_view_tran_qry = "SELECT * from loop_transaction WHERE id = " . $rec_id;
                db();
                $dt_view_tran = db_query($dt_view_tran_qry);
                $dt_view_tran_row = array_shift($dt_view_tran);
                ?>
        <tr bgColor="#e4e4e4">
            <td colspan="3" height="13" class="style12right">
                Freight: </td>
            <td height="13" class="style1" align="left" colspan="9">
                <?php echo number_format($dt_view_tran_row["freightcharge"], 2); ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td colspan="3" height="13" class="style12right">
                Other Charges: </td>
            <td height="13" class="style1" align="left" colspan="9">
                <?php echo number_format($dt_view_tran_row["othercharge"], 2); ?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td colspan="3" height="13" class="style12right">
                Other Details: </td>
            <td height="13" class="style1" align="left" colspan="9">
                <?php echo $dt_view_tran_row["otherdetails"]; ?>
            </td>
        </tr>
        <!-- Added by Mooneem on Apr-24-12 -->
        <tr bgColor="#e4e4e4">
            <td colspan="3" height="13" class="style12right"> Total Amount: </td>
            <td height="13" class="style1bold" align="left" colspan="9">
                <?php echo number_format(isset($goodvalue) + isset($badvalue) + $dt_view_tran_row["freightcharge"] + $dt_view_tran_row["othercharge"], 2);             ?>
            </td>
        </tr> <!-- Added by Mooneem on Apr-24-12 -->
        <tr bgColor="#e4e4e4">
            <td colspan="3" height="13" class="style12right">
                Notes: </td>
            <td height="13" class="style1" align="left" colspan="9">
                <p align="left"><?php echo $dt_view_row["boxnotes"]; ?>
            </td>
        </tr>
    </font>

    <tr bgColor="#e4e4e4">
        <td colspan="3" height="13" class="style12right">
            Employee: </td>
        <td height="13" class="style1" align="left" colspan="9">
            <p align="left"><?php echo $dt_view_row["employee"]; ?></font>
        </td>
        </font>
        </font>
        </font>
    </tr>
    <tr bgColor="#e4e4e4">
        <td colspan="3" height="13" class="style12right">
            Date: </td>
        <td height="13" class="style1" align="left" colspan="9">
            <p align="left"><?php echo $dt_view_row["sort_date"]; ?>
        </td>
    </tr>
    <?php
        } ?>

</table>
<?php } ?>



<br>


<?php

$dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "' AND usr_file != ''";
db();
$dt_view_res = db_query($dt_view_qry);
$num_rows = tep_db_num_rows($dt_view_res);
if ($num_rows > 0) {
?>



<table cellSpacing="1" cellPadding="1" border="0" style="width: 500px" id="table13">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="3">
            <font size="1">UPLOAD BOL, SORT REPORT & ALL RELATED PAPER WORK TO TRAILER - TRAILER
                #<?php echo $trailer_number; ?></font>
        </td>
    </tr>


    <?php
        while ($dt_view_row = array_shift($dt_view_res)) {
            $usrfile = $dt_view_row["usr_file"];
        ?>


    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            File</td>
        <td height="13" class="style1" align="left" colspan="2">
            <?php if ($dt_view_row["bol_file"] == 'No Sort Report') { ?>
            No Sort Report
            <?php } else {
                        if (file_exists("files/" . $dt_view_row["usr_file"])) {
                        ?>
            <a target="_blank" href="./files/<?php echo $dt_view_row["usr_file"]; ?>">View File:
                <?php echo $dt_view_row["usr_file"]; ?></a>
            <?php
                        } else { ?>
            <a target="_blank" href="bol/<?php echo $dt_view_row["usr_file"]; ?>">View File:
                <?php echo $dt_view_row["usr_file"]; ?></a>
            <?php }
                    } ?>
        </td>
    </tr>

    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Amount</td>
        <td height="13" class="style1" align="left" colspan="2">
            <?php echo $dt_view_row["usr_amount"]; ?>
        </td>
    </tr>

    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Employee</td>
        <td height="13" class="style1" align="left" colspan="2">

            <?php echo $dt_view_row["usr_employee"]; ?>
        </td>
    </tr>


    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Date Entered</td>
        <td height="13" class="style1" align="left" colspan="2">

            <?php echo $dt_view_row["usr_date"]; ?>
        </td>
    </tr>



    <?php } ?>
</table>
<?php } ?>

<br>



<script type="text/javascript">
<!--
function check_form(SortReport) {
    var error = 0;
    var checkstring = "Please take a moment to complete required fields:\n";

    if (document.SortReport.file.value == "") {
        checkstring = checkstring + "Upload Sort Report\n";
        error = 1;
    }

    if (error == 1) {
        alert(checkstring);
        return false;
    }


}
//
-->
</SCRIPT>

<form action="addusrreport_mrg.php" method="post" encType="multipart/form-data" name="SortReport"
    onSubmit="return check_form(SortReport)">
    <input type="hidden" name="ID" value="<?php echo $_REQUEST['ID']; ?>">
    <input type="hidden" name="warehouse_id" value="<?php echo isset($id); ?>" />
    <input type="hidden" name="rec_type" value="<?php echo isset($rec_type); ?>" />
    <input type="hidden" name="recipient" value="<?php echo isset($warehouse_contact_email); ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 500px" id="table7">
        <tr align="middle">
            <?php
            if ($usrfile != "") {
            ?>
            <td bgColor="#99FF99" width="500" colspan="2">
                <?php
            } else {
                ?>
            <td bgColor="#fb8a8a" width="500" colspan="2">
                <?php
            }
                ?>
                <font Face='arial' size='1'>
                    UPLOAD BOL, SORT REPORT & ALL RELATED PAPER WORK TO TRAILER - TRAILER
                    #<?php echo $trailer_number; ?></font>
            </td>
        </tr>


        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="center">
                <p align="right">Report:
            </td>
            <td height="13" class="style1" align="center">
                <input type=file name="file" size="32"><br>
                <input type=submit value="Upload Report" style="cursor:pointer;">
            </td>
        </tr>


    </table>

</form>



<br>

<!------------------------------ Start ORDER ISSUE PICTURES FROM 2nd BUBBLE ------------------------------>
<?php

$virtual_rec_found = 0;
$qry = "select ID, on_hold from companyInfo WHERE ID =" . $_REQUEST['ID'];
db_b2b();
$qry_res = db_query($qry);
$c_row = array_shift($qry_res);
$comp_onhold = $c_row["on_hold"];
//

db();
$sql_ord = db_query("SELECT *, virtual_inventory_company_id, virtual_inventory_trans_id FROM loop_transaction_buyer WHERE virtual_inventory_trans_id > 0 and loop_transaction_buyer.virtual_inventory_trans_id = '" . $_REQUEST["rec_id"] . "' and loop_transaction_buyer.virtual_inventory_company_id = '" . $_REQUEST["warehouse_id"] . "' ");

//echo "SELECT *, virtual_inventory_company_id, virtual_inventory_trans_id FROM loop_transaction_buyer WHERE virtual_inventory_trans_id > 0 and loop_transaction_buyer.virtual_inventory_trans_id = '". $_REQUEST["rec_id"] . "' and loop_transaction_buyer.virtual_inventory_company_id = '". $_REQUEST["warehouse_id"] . "' ";
if (tep_db_num_rows($sql_ord) > 0) {
    $virtual_rec_found = 1;
    while ($data_row = array_shift($sql_ord)) {
        $sales_tansid = $data_row["id"];
        $sales_warehouseid = $data_row["warehouse_id"];
        $order_issue_pictures_val = $data_row["order_issue_pictures"];
        $sales_rec_type = $data_row["rec_type"];
    }
}
if ($sales_warehouseid > 0) {

    db();
    $sql_comp = db_query("SELECT loop_warehouse.b2bid FROM loop_warehouse WHERE loop_warehouse.id = '" . $sales_warehouseid . "'");
    while ($com_row = array_shift($sql_comp)) {
        $sales_company_id = $com_row["b2bid"];
    }
}
//echo "<br>".$virtual_rec_found;
//
if ($comp_onhold == 1 && $virtual_rec_found == 1) {

    //}
    //else{
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
        $img_qry = "Select * from order_issue_pictures Where trans_id = " . $sales_tansid . " ORDER by id ASC";
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
                <tr bgColor="#e4e4e4" id="orderissue<?php echo $img_row["id"]; ?>">
                    <td align="center" style="padding: 4px;" width="220px">
                        <a href="#"
                            onclick="view_orderissue_img('<?php echo 'orderissuepic/' . $img_row["order_img"]; ?>', <?php echo $img_row["id"]; ?>);">
                            <img src="orderissuepic/<?php echo $img_row["order_img"]; ?>" width="50" height="auto">
                        </a><br>

                    </td>
                    <td align="left" style="padding: 4px; padding-left: 12px;"><a
                            style='color:#E00003; text-decoration: none; font-weight: 600;' href="javascript:void(0);"
                            onclick="order_issue_img_delete(<?php echo $img_row["id"]; ?>,<?php echo $sales_tansid; ?>,<?php echo $sales_warehouseid; ?>,<?php echo $sales_company_id; ?>)">
                            X</a></td>
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
                <input type="hidden" name="comp_id" value="<?php echo $sales_company_id; ?>">
                <input type="hidden" name="rec_id" value="<?php echo $sales_tansid; ?>">
                <input type="hidden" id="rec_type" name="rec_type" value="<?php echo $sales_rec_type; ?>">
                <input type="hidden" name="warehouse_id" value="<?php echo $sales_warehouseid; ?>">
            </td>
        </tr>
        <table>
</form>
<?php
}
?>
<!------------------------------ End ORDER ISSUE PICTURES FROM 2nd BUBBLE ------------------------------>

<br>


</font>
</font>

</font>
</font>