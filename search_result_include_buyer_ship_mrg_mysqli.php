<div id="sch_eml_p1" />

<iframe frameborder="0" onload="iframeLoaded_ship_pickup_or_ucb_delivering()" scrolling="auto"
    id="iframe_ship_pickup_or_ucb_delivering"
    src="loop_shipbubble_pickup_or_ucb_delivering.php?rec_id=<?php echo $_REQUEST['rec_id']; ?>&ID=<?php echo $_REQUEST["ID"]; ?>&rec_id=<?php echo $_REQUEST["rec_id"]; ?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"]; ?>&rec_type='<?php echo $_REQUEST["rec_type"]; ?>'">

</iframe>

<script>
function iframeLoaded_ship_pickup_or_ucb_delivering() {
    ifrmaeobj = document.getElementById("iframe_ship_pickup_or_ucb_delivering");
    var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
    objheight = objheight + 20;
    ifrmaeobj.style.height = objheight + 'px';
    ifrmaeobj.style.width = '600px';
}
</script>

<Font Face='arial' size='2'>

    <br>

    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <script type="text/javascript" src="wz_tooltip.js"></script>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal1xx = new CalendarPopup("listdiv");
    cal1xx.showNavigationDropdowns();
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();
    var cal3xx = new CalendarPopup("listdiv");
    cal3xx.showNavigationDropdowns();
    var cal4xx = new CalendarPopup("listdiv");
    cal4xx.showNavigationDropdowns();
    var cal5xx = new CalendarPopup("listdiv");
    cal5xx.showNavigationDropdowns();
    </script>


    <script type="text/javascript">
    function check_form(uploadBOL) {
        var error = 0;
        var checkstring = "Please take a moment to complete required fields:\n";

        if (document.uploadBOL.file.value == "") {
            checkstring = checkstring + "Upload Signed BOL\n";
            error = 1;
        }

        if (error == 1) {
            alert(checkstring);
            return false;
        }

    }

    function viewpicklist(rec_id) {
        parent.window.location.href = 'https://loops.usedcardboardboxes.com/picklist.php?rec_id=' + rec_id;
    }

    function reminder_popup_set_confrm_ship(compid, rec_id, warehouse_id, rec_type, pickup_or_ucb_delivering) {
        delv_dt = document.getElementById('iframe_ship_bol').contentWindow.document.getElementById("delivery_date")
            .value;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("iframe_ship_bol");
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                var getele = document.getElementById("iframe_ship_bol").contentWindow.document.getElementById(
                    "reminder_popup_set4_btn");

                n_left1 = f_getPosition(selectobject, 'Left');
                n_top1 = f_getPosition(selectobject, 'Top');
                popupbox = getele.getBoundingClientRect();

                n_top = popupbox.top;
                n_right = popupbox.right + window.pageXOffset;
                n_bottom = popupbox.bottom + window.pageYOffset;
                n_left = popupbox.left + window.pageXOffset;

                n_top_cnt = n_top1 + n_top;

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = (n_left + 20) + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';

                if (pickup_or_ucb_delivering == 1) {
                    document.getElementById('light_reminder').style.top = n_top_cnt + 'px';
                }
                if (pickup_or_ucb_delivering == 2) {
                    document.getElementById('light_reminder').style.top = n_top_cnt + 'px';
                }
                document.getElementById('light_reminder').style.width = 1100 + 'px';

            }
        }

        xmlhttp.open("POST", "sendemail_confrim_shipped_new.php?compid=" + compid + "&rec_id=" + rec_id +
            "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type + "&delv_dt=" + delv_dt, true);
        xmlhttp.send();
    }

    function chkforminp() {
        var flg = 0;
        if (document.getElementById("shiptoname").value == "") {
            alert("Please enter the Ship To Name.");
            flg = 0;
            return false;
        } else {
            flg = 1;
        }

        if (document.getElementById("shiptoeml").value == "") {
            alert("Please enter the Ship To Email.");
            flg = 0;
            return false;
        } else {
            flg = 1;
        }

        if (document.getElementById("delivery_date").value == "") {
            alert("Please enter the Delivery Date.");
            flg = 0;
            return false;
        } else {
            flg = 1;
        }

        var retval = confirm("Do you wish to Send the email?");
        if (retval == true) {
            flg = 1;
        } else {
            flg = 0;
            return false;
        }

        if (flg == 1) {
            document.frmbol_confirmshipped_sendemail.submit();
            return true;
        }
    }

    function chkforminp_sch() {
        var flg = 0;
        if (document.getElementById("sch_shiptoname").value == "") {
            alert("Please enter the Ship To Name.");
            flg = 0;
            return false;
        } else {
            flg = 1;
        }

        if (document.getElementById("sch_shiptoeml").value == "") {
            alert("Please enter the Ship To Email.");
            flg = 0;
            return false;
        } else {
            flg = 1;
        }


        var retval = confirm("Do you wish to Send the email?");
        if (retval == true) {
            flg = 1;
        } else {
            flg = 0;
            return false;
        }

        if (flg == 1) {
            document.frmscheduling_order_sendemail.submit();
            return true;
        }
    }

    function reminder_popup_set2(compid, rec_id, warehouse_id, rec_type) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("sch_eml_p1");
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left + 'px';
                document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "scheduling_order_sendemail.php?compid=" + compid + "&rec_id=" + rec_id +
            "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }

    function reminder_popup_truck_felloff(compid, rec_id, warehouse_id, rec_type) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("truckfellof_eml");
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left + 'px';
                document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "truckfellof_sendemail.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
            warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }

    function reminder_popup_truck_felloff_broker(compid, rec_id, warehouse_id, rec_type) {
        selectobject = document.getElementById("truckfellof_eml_broker");
        n_left = f_getPosition(selectobject, 'Left');
        n_top = f_getPosition(selectobject, 'Top');

        document.getElementById("light_reminder").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        document.getElementById('light_reminder').style.display = 'block';

        document.getElementById('light_reminder').style.left = n_left + 'px';
        document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
        document.getElementById('light_reminder').style.width = 1100 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST", "truckfellof_sendemail_broker.php?compid=" + compid + "&rec_id=" + rec_id +
            "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }

    function reminder_popup_bol_shipper(compid, rec_id, warehouse_id, rec_type, pickup_or_ucb_delivering) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("iframe_ship_bol");

                var getele = document.getElementById("iframe_ship_bol").contentWindow.document.getElementById(
                    "emailBOL");

                n_left1 = f_getPosition(selectobject, 'Left');
                n_top1 = f_getPosition(selectobject, 'Top');
                popupbox = getele.getBoundingClientRect();

                n_top = popupbox.top;
                n_right = popupbox.right + window.pageXOffset;
                n_bottom = popupbox.bottom + window.pageYOffset;
                n_left = popupbox.left + window.pageXOffset;

                n_top_cnt = n_top1 + n_top;

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left + 'px';
                if (pickup_or_ucb_delivering == 1) {
                    document.getElementById('light_reminder').style.top = n_top_cnt + 'px';
                }
                if (pickup_or_ucb_delivering == 2) {
                    document.getElementById('light_reminder').style.top = n_top_cnt + 'px';
                }
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "loop_ship_sendemail_bol.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
            warehouse_id + "&rec_type=" + rec_type + "&pickup_or_ucb_delivering=" + pickup_or_ucb_delivering, true);
        xmlhttp.send();
    }
    /*-------------------------------------------------------------------------------
    Ignore function added.
    function bol_ignore(po_ignore_flg, compid, rec_id, warehouse_id, rec_type)
    -------------------------------------------------------------------------------*/
    function bol_ignore(po_ignore_flg, rec_id, warehouse_id) {
        var ele = document.getElementById("iframe_ship_bol").contentWindow.document.getElementById("bol_div_ignore");
        ele.innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                ele.innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST", "bol_received_ignore.php?bol_received_ignore=1&rec_id=" + rec_id + "&warehouse_id=" +
            warehouse_id, true);
        xmlhttp.send();
    }

    /*-------------------------------------------------------------------------------
    Added by Amarendra dated 11-05-2021 
    -------------------------------------------------------------------------------*/

    function reminder_popup_set2_customer_pickup(compid, rec_id, warehouse_id, rec_type) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("sch_eml_p1");
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left + 'px';
                document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "scheduling_order_sendemail_customer_pickup.php?compid=" + compid + "&rec_id=" + rec_id +
            "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }

    function btnsendemlclick_eml_p4() {
        var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

        tmp_element1 = document.getElementById("txtemailto").value;

        tmp_element2 = document.getElementById("email_reminder_sch_p4");

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

    }

    function btnsendemlclick_eml_p2() {
        var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

        tmp_element1 = document.getElementById("txtemailto").value;

        tmp_element2 = document.getElementById("email_reminder_sch_p2");

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

    }

    function btnsendemlclick_eml_p3() {
        var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

        tmp_element1 = document.getElementById("txtemailto").value;

        tmp_element2 = document.getElementById("email_reminder_sch_p3");

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

    }

    function btnsendeml_truck_felloff() {
        var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

        tmp_element1 = document.getElementById("txtemailto").value;

        tmp_element2 = document.getElementById("email_reminder_sch_p2");

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

    }

    function getlatest_notes(rec_id, warehouse_id) {
        document.getElementById('frm_trans_notes_td').innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp_child = new XMLHttpRequest();
        } else {
            xmlhttp_child = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp_child.onreadystatechange = function() {
            if (xmlhttp_child.readyState == 4 && xmlhttp_child.status == 200) {
                document.getElementById('frm_trans_notes_td').innerHTML = xmlhttp_child.responseText;
            }
        }

        xmlhttp_child.open("GET", "search_result_include_crm_forajax.php?warehouse_id=" + warehouse_id + "&rec_id=" +
            rec_id + "&rec_type=Supplier", true);
        xmlhttp_child.send();
    }

    function reminder_popup_set3(compid, rec_id, warehouse_id, rec_type) {

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("sch_eml_p1");
                var getele = document.getElementById("iframe_ship_pickup_or_ucb_delivering").contentWindow.document
                    .getElementById("btnsendfreml");

                n_left1 = f_getPosition(selectobject, 'Left');
                n_top1 = f_getPosition(selectobject, 'Top');
                popupbox = getele.getBoundingClientRect();

                n_top = popupbox.top;
                n_right = popupbox.right + window.pageXOffset;
                n_bottom = popupbox.bottom + window.pageYOffset;
                n_left = popupbox.left + window.pageXOffset;

                n_top_cnt = n_top1 + n_top;

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left + 'px';
                document.getElementById('light_reminder').style.top = n_top_cnt + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "sendemail_freight_booking.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
            warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }


    function reminder_popup_set4_cust(compid, rec_id, warehouse_id, rec_type) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("sch_eml_p1");

                var getele = document.getElementById("iframe_ship_pickup_or_ucb_delivering").contentWindow.document
                    .getElementById("btnsendbrokerneedpkp_cust");

                n_left1 = f_getPosition(selectobject, 'Left');
                n_top1 = f_getPosition(selectobject, 'Top');
                popupbox = getele.getBoundingClientRect();

                n_top = popupbox.top;
                n_right = popupbox.right + window.pageXOffset;
                n_bottom = popupbox.bottom + window.pageYOffset;
                n_left = popupbox.left + window.pageXOffset;

                n_top_cnt = n_top1 + n_top;

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left + 'px';
                document.getElementById('light_reminder').style.top = n_top_cnt + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "sendemail_broker_needs_pickup_cust.php?compid=" + compid + "&rec_id=" + rec_id +
            "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }

    function reminder_popup_set4(compid, rec_id, warehouse_id, rec_type) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("sch_eml_p1");

                var getele = document.getElementById("iframe_ship_pickup_or_ucb_delivering").contentWindow.document
                    .getElementById("btnsendbrokerneedpkp");

                n_left1 = f_getPosition(selectobject, 'Left');
                n_top1 = f_getPosition(selectobject, 'Top');
                popupbox = getele.getBoundingClientRect();

                n_top = popupbox.top;
                n_right = popupbox.right + window.pageXOffset;
                n_bottom = popupbox.bottom + window.pageYOffset;
                n_left = popupbox.left + window.pageXOffset;

                n_top_cnt = n_top1 + n_top;

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left + 'px';
                document.getElementById('light_reminder').style.top = n_top_cnt + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "sendemail_broker_needs_pickup.php?compid=" + compid + "&rec_id=" + rec_id +
            "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }
    </SCRIPT>


    <?php


    $shipto_name = "";
    $shipto_email = "";
    $assignedto = "";
    $account_owner_email = "";
    $sql_x = "Select shipemail, shipContact, assignedto from companyInfo Where ID = " . isset($b2bid);
    db_b2b();
    $dt_view_res_n = db_query($sql_x);
    while ($row_forb2b = array_shift($dt_view_res_n)) {
        $shipto_name = $row_forb2b["shipContact"];
        $shipto_email = $row_forb2b["shipemail"];
        $assignedto = $row_forb2b["assignedto"];
    }

    $sql_x = "SELECT email FROM employees where employeeID=" . $assignedto;
    db_b2b();
    $dt_view_res_n = db_query($sql_x);
    while ($row_forb2b = array_shift($dt_view_res_n)) {
        $account_owner_email = $row_forb2b["email"];
    }

    db();
    $result_n = db_query("SELECT location_warehouse_id FROM loop_salesorders WHERE trans_rec_id = " . $_REQUEST["rec_id"]);
    while ($myrowsel_n = array_shift($result_n)) {
        $loc_warehouse_id = $myrowsel_n["location_warehouse_id"];
    }

    $warehouse_calendly_link = "";
    if (isset($loc_warehouse_id) != "") {

        db();
        $result_n = db_query("SELECT calendly_link FROM loop_warehouse WHERE id = " . isset($loc_warehouse_id));
        while ($myrowsel_n = array_shift($result_n)) {
            $warehouse_calendly_link = $myrowsel_n["calendly_link"];
        }
    }

    $customerpickup_ucbdelivering_flg = "";
    $virtual_inventory_trans_id = 0;
    db();
    $getdata = db_query("Select virtual_inventory_trans_id, customerpickup_ucbdelivering_flg From loop_transaction_buyer where id = " . $_REQUEST["rec_id"]);
    while ($getdata_row = array_shift($getdata)) {
        $customerpickup_ucbdelivering_flg = $getdata_row["customerpickup_ucbdelivering_flg"];
        $virtual_inventory_trans_id = $getdata_row["virtual_inventory_trans_id"];
    }

    //if ($warehouse_calendly_link != "") {
    ?>
    <iframe frameborder="0" onload="iframeLoaded_ship_freight_booking()" scrolling="auto"
        id="iframe_ship_freight_booking"
        src="loop_shipbubble_freight_booking.php?rec_id=<?php echo $_REQUEST['rec_id']; ?>&ID=<?php echo $_REQUEST["ID"]; ?>&rec_id=<?php echo $_REQUEST["rec_id"]; ?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"]; ?>&rec_type='<?php echo $_REQUEST["rec_type"]; ?>'">

    </iframe>

    <script>
    function iframeLoaded_ship_freight_booking() {
        ifrmaeobj = document.getElementById("iframe_ship_freight_booking");
        var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
        objheight = objheight + 150;
        ifrmaeobj.style.height = objheight + 'px';
        //ifrmaeobj.style.width = '600px';		
    }
    </script>
    <?php //} 
    ?>
    <!------------------------------ Start Truck Fell off ------------------------------>

    <?php


    if ($customerpickup_ucbdelivering_flg == "2") {

        $rec_found = "n";
        $eml_sendon = "";
        $eml_sendby = "";
        $rec_found2 = "n";
        db();
        $getdata = db_query("Select customer_flg, email_sendon, email_sendby From loop_transaction_buyer_truck_felloff where trans_rec_id = " . $_REQUEST["rec_id"] . " ");
        while ($getdata_row = array_shift($getdata)) {
            $rec_found2 = "y";
            if ($getdata_row["customer_flg"] == 1) {
                $rec_found = "y";

                $eml_sendon = $getdata_row["email_sendon"];
                $eml_sendby = $getdata_row["email_sendby"];
            }
        }
    ?>
    <table cellSpacing="1" cellPadding="1" border="0" style="width: 400px">
        <tr align="middle">
            <?php if ($rec_found2 == "n") { ?>
            <td bgColor="#c0cdda" colSpan="2">
                <?php } else { ?>
            <td bgColor="#99FF99" colSpan="2">
                <?php } ?>
                <font size="1">Truck Fell Off?</font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td align="center" height="13" colspan="2" class="style1">
                <?php if (isset($freightupdates) == 0) {
                        echo "<font size=1 color=red>OPT OUT</font>";
                    } ?>

                <?php if ($rec_found == "n") { ?>
                <input type="button" id="truckfellof_eml" value="Send Customer E-mail"
                    onclick="reminder_popup_truck_felloff(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,'<?php echo $_REQUEST["rec_type"]; ?>')">
                <?php     } else {  ?>
                <input type="button" id="truckfellof_eml" value="Re-Send Customer E-mail"
                    onclick="reminder_popup_truck_felloff(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,'<?php echo $_REQUEST["rec_type"]; ?>')">
                <?php
                        echo "Customer Email Sent on " . $eml_sendon . " by " . $eml_sendby;
                    }  ?>
                <?php
                    $rec_found = "n";
                    $eml_sendon = "";
                    $eml_sendby = "";
                    db();
                    $getdata = db_query("Select email_sendon, email_sendby From loop_transaction_buyer_truck_felloff where trans_rec_id = " . $_REQUEST["rec_id"] . " and customer_flg = 2 ");
                    while ($getdata_row = array_shift($getdata)) {
                        $rec_found = "y";
                        $eml_sendon = $getdata_row["email_sendon"];
                        $eml_sendby = $getdata_row["email_sendby"];
                    }
                    ?>
                <?php if ($rec_found == "n") { ?>
                &nbsp;
                <input type="button" id="truckfellof_eml_broker" value="Send Broker Blast E-mail"
                    onclick="reminder_popup_truck_felloff_broker(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,'<?php echo $_REQUEST["rec_type"]; ?>')">
                <?php     } else { ?>
                <input type="button" id="truckfellof_eml_broker" value="Re-Send Broker Blast E-mail"
                    onclick="reminder_popup_truck_felloff_broker(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,'<?php echo $_REQUEST["rec_type"]; ?>')">
                <?php
                        echo "&nbsp;Broker Email Sent on " . $eml_sendon . " by " . $eml_sendby;
                    }
                    ?>

            </td>
        </tr>
    </table>
    <br><br>
    <?php } ?>
    <!------------------------------ End Truck Fell off ------------------------------>

    <!------------------------------ BEGIN SALES ORDER ------------------------------>

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table13">
        <tr align="middle">
            <td bgColor="#c0cdda" colSpan="6">
                <font size="1">SALES ORDER</font>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 84px" class="style1" align="center">
                Quantity</td>
            <td height="13" style="width: 94px" class="style1" align="center">
                Warehouse</td>
            <td height="13" style="width: 30px" class="style1" align="center">
                B2B ID</td>
            <td align="left" style="width: 578px" height="13" class="style1">
                Description</td>
            <td align="left" style="width: 200px" height="13" class="style1">
                Supplier</td>
            <td align="left" style="width: 300px" height="13" class="style1">
                Ship From</td>
        </tr>

        <!---Inventory Items---->

        <?php

        $get_sales_order = db_query("Select *, loop_salesorders.notes AS A, loop_salesorders.pickup_date AS B, loop_salesorders.freight_vendor AS C, loop_salesorders.time AS D From loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " . $_REQUEST['rec_id']);

        while ($boxes = array_shift($get_sales_order)) {
            $so_notes = $boxes["A"];
            $so_pickup_date = $boxes["B"];
            $so_freight_vendor = $boxes["C"];
            $so_time = $boxes["D"];

            $b2b_vendor = 0;
            $ship_from = "";
            $vendor_b2b_rescue = 0;
            db_b2b();
            $sql_getdata = db_query("SELECT  location_city, location_state, vendor_b2b_rescue, location_zip, ulineDollar, ulineCents, costDollar, costCents , vendor FROM inventory WHERE ID = " . $boxes["b2b_id"]);


            while ($rowsel_getdata = array_shift($sql_getdata)) {
                $b2b_vendor = $rowsel_getdata["vendor"];
                $vendor_b2b_rescue = $rowsel_getdata["vendor_b2b_rescue"];
                $ship_from = $rowsel_getdata["location_city"] . ", " . $rowsel_getdata["location_state"] . " " . $rowsel_getdata["location_zip"];
            }

            $vender_nm = "";
            if ($vendor_b2b_rescue != "") {
                $qry_supplier = "SELECT id, company_name, b2bid FROM loop_warehouse where id = " . $vendor_b2b_rescue;
                db();
                $res_supplier = db_query($qry_supplier);
                while ($fetch_supplier = array_shift($res_supplier)) {
                    $vender_nm = get_nickname_val($fetch_supplier['company_name'], $fetch_supplier["b2bid"]) . " (Loop ID: " . $fetch_supplier["id"] . " B2B ID:" . $fetch_supplier["b2bid"] . ")";
                }
            }

        ?>
        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="right">
                <Font Face='arial' size='1'><?php echo $boxes["qty"]; ?>
            </td>
            <td height="13" style="width: 250px" class="style1" align="left">
                <Font Face='arial' size='1'>
                    <?php

                        $get_wh = "SELECT * FROM loop_warehouse WHERE id = " . $boxes["location_warehouse_id"];
                        db();
                        $get_wh_res = db_query($get_wh);
                        while ($the_wh = array_shift($get_wh_res)) {
                            echo $the_wh["warehouse_name"];
                        }
                        ?>
            </td>
            <td height="13" class="style1" align="right">
                <Font Face='arial' size='1'><?php echo $boxes["b2b_id"]; ?>
            </td>

            <td align="left" height="13" style="width: 250px" class="style1">
                <a target="_blank" href="manage_box_b2bloop.php?id=<?php echo $boxes["id"]; ?>&proc=View">
                    <font size="1" Face="arial"><?php echo $boxes["bdescription"]; ?></font>
                </a>
            </td>
            <td align="left" height="13" style="width: 200px" class="style1">
                <?php echo $vender_nm; ?>
            </td>
            <td align="left" height="13" style="width: 200px" class="style1">
                <?php echo $ship_from; ?>
            </td>
        </tr>

        <?php

        }
        ?>

        <!------- Manual Items -------------->
        <?php

        $soqry = "Select * From loop_salesorders_manual WHERE trans_rec_id = " .  $_REQUEST['rec_id'];

        db();
        $get_sales_order2 = db_query($soqry);
        while ($boxes2 = array_shift($get_sales_order2)) {
        ?>
        <tr bgColor="#ff0000">
            <td height="13" class="style1" align="right">
                <Font Face='arial' size='1'><?php echo $boxes2["qty"]; ?>
            </td>

            <td align="left" height="13" style="width: 578px" class="style1" colspan=5>
                <font size="1" Face="arial">&nbsp;&nbsp;<?php echo $boxes2["description"]; ?></font>
            </td>
        </tr>
        <?php
        }
        ?>
        <!---------- End Manual Items   ----------------->

        <?php while ($so_view_row = array_shift($so_view_res)) { ?>

        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="left">Notes</td>
            <td colspan=2 height="13" class="style1" align="left"><?php echo isset($so_notes); ?></td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="left">File</td>
            <td height="13" class="style1" align="left">Employee</td>
            <td height="13" class="style1" align="left">Date</td>
        </tr>


        <tr bgColor="#e4e4e4">

            </td>

            <td height="13" class="style1" align="left">
            </td>
            <td height="13" class="style1" align="left"> <?php echo $so_view_row["so_employee"]; ?>
            </td>
            <td height="13" class="style1" align="left"> <?php echo $so_view_row["so_date"]; ?>
            </td>


        </tr>


        <?php } ?>
    </table>

    <br><br>
    <!------------------------------ END SALES ORDER ------------------------------>

    <!--PRINT PICK LIST table start-->

    <?php

    db();
    $resPrintDtls = db_query("SELECT picklist_print, picklist_printtime, picklist_printby FROM loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"]);
    $rowPrintDtls = array_shift($resPrintDtls);
    //echo "<pre>"; print_r($rowPrintDtls); echo "</pre>";
    $picklist_print = $rowPrintDtls["picklist_print"];

    if ($picklist_print != 'Y') {
    ?>
    <form METHOD="POST" action="printpicklist_save.php">
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px">
            <tr align="middle">
                <td bgColor="#fb8a8a" colspan="2">
                    <font size="1">PRINT PICK LIST</font>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td align="center" height="13" style="width: 235px" class="style1">
                    <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
                    <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
                    <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
                    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
                    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
                    <input type="hidden" name="printpicklist" value="yes" />
                    <input type="hidden" name="userinput" id="userinput" value="" />

                    <input type="button" name="btnviewpicklist" id="btnviewpicklist"
                        onclick="viewpicklist(<?php echo $_REQUEST["rec_id"]; ?>)" value="View Pick List" />
                    &nbsp;
                    <input type="submit" name="btnPrintopt" id="btnPrintopt" onclick="confirmationPrint()"
                        value="Update Print status" />

                </td>
            </tr>
        </table>
    </form>
    <?php

    } else {

    ?>
    <form METHOD="POST" action="printpicklist_save.php">
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px">
            <tr align="middle">
                <td bgColor="#99FF99" colspan="2">
                    <font size="1">PRINT PICK LIST</font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="left" height="13" style="width: 235px" class="style1" colspan="2">
                    <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
                    <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
                    <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
                    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
                    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
                    <input type="hidden" name="printpicklist" value="undo" />

                    <span>Sort report printed on
                        <?php echo  date('m/d/Y H:i:s', strtotime($rowPrintDtls['picklist_printtime'])); ?> by
                        <?php echo $rowPrintDtls['picklist_printby'] ?> </span>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan="2" style="width: 235px" class="style1">
                    <input type="submit" value="UNDO" id="btnUndoPrintStatus" name="btnUndoPrintStatus">
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan="2" style="width: 235px" class="style1">&nbsp;</td>
            </tr>
        </table>
    </form>

    <?php
    }
    ?>
    <!--PRINT PICK LIST table end-->

    <br><br>
    <iframe frameborder="0" style="width:100%;" onload="iframeLoaded_ship_bol()" scrolling="auto" id="iframe_ship_bol"
        src="loop_shipbubble_bol.php?rec_id=<?php echo $_REQUEST['rec_id']; ?>&ID=<?php echo $_REQUEST["ID"]; ?>&rec_id=<?php echo $_REQUEST["rec_id"]; ?>&warehouse_id=<?php echo $_REQUEST["warehouse_id"]; ?>&rec_type='<?php echo $_REQUEST["rec_type"]; ?>'">

    </iframe>

    <script>
    function iframeLoaded_ship_bol() {
        ifrmaeobj = document.getElementById("iframe_ship_bol");
        var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
        objheight = objheight + 50;
        ifrmaeobj.style.height = objheight + 'px';
    }
    </script>



</font>
</font>