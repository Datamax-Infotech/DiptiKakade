<Font Face='arial' size='2'>
    <br>

    <style>
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

    .white_content_details {
        display: none;
        position: absolute;
        top: 0%;
        left: 10%;
        width: 50%;
        height: auto;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
        box-shadow: 8px 8px 5px #888888;
    }


    .white_content_reminder {
        display: none;
        position: absolute;
        top: 10%;
        left: 10%;
        width: 70%;
        height: 85%;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
        box-shadow: 8px 8px 5px #888888;
    }
    </style>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
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
    </script>
    <script>
    function btnsendemlclick_eml_p() {
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

    }
    </script>

    <script language="JavaScript">
    <!--
    function ChgTextPR() {
        var MyElement = document.getElementById("pr_requestby");
        MyElement.value = "No Pickup Arrangement";

        var MyElement = document.getElementById("pr_requestdate");
        MyElement.value = "No Request Date";

        var MyElement = document.getElementById("pr_pickupdate");
        MyElement.value = "No Pickup Date";

        var MyElement = document.getElementById("pr_dock");
        MyElement.value = "No Pickup Dock";

        var MyElement = document.getElementById("pr_trailer");
        MyElement.value = "No Pickup Trailer";
        return true;
    }


    function ChgTextPA() {
        var MyElement = document.getElementById("MyTextBox");
        MyElement.value = "If you see this, it worked!";

        return true;
    }

    function ChgTextCP() {
        var MyElement = document.getElementById("cp_notes");
        MyElement.value = "No Confirmation Arrangement.";

        document.getElementById("CP").submit();
    }


    function goToOptionPA(sel, val) {
        var opt, o = 0;
        while (opt = sel[o++])
            if (opt.value == val) sel.selectedIndex = o - 1;

        var MyElement = document.getElementById("pa_vendor_broker_hauler");
        MyElement.value = "No Broker";

        var MyElement = document.getElementById("pa_pickupdate");
        MyElement.value = "No Pickup Date";

        var MyElement = document.getElementById("pa_publicnotes");
        MyElement.value = "No Public Notes";

        var MyElement = document.getElementById("pa_internalnotes");
        MyElement.value = "No Internal Notes";

    }

    function goToOptionPAA(sel, val) {
        var opt, o = 0;
        while (opt = sel[o++])
            if (opt.value == val) sel.selectedIndex = o - 1;
    }

    function goToOption(sel, val) {

        var MyElement = document.getElementById("dt_trailer");
        MyElement.value = "No Trailer";

        var opt, o = 0;
        while (opt = sel[o++])
            if (opt.value == val) sel.selectedIndex = o - 1;

        return true;
    }

    function FormCheck() {
        if (document.AddDT.dt_vendor.value == "") {
            alert("Please select a Vendor.  Thank you.");
            return false;
        }
    }
    //
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

    //
    function pr_ignore(pr_ignore_flg, compid, rec_id, warehouse_id, rec_type) {
        if (pr_ignore_flg == 'prsendemail_ignore') {
            document.getElementById("tbl_pr_send_email").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (pr_ignore_flg == 'prsendemail_ignore') {
                    document.getElementById("tbl_pr_send_email").innerHTML = xmlhttp.responseText;
                }

            }
        }

        if (pr_ignore_flg == 'prsendemail_ignore') {
            xmlhttp.open("POST", "pr_bubble_email.php?pr_prsendemail_ignore=1&ID=" + compid + "&rec_id=" + rec_id +
                "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        }

        xmlhttp.send();
    }
    //
    function po_sent_ignore(posent_to_supplier_ignore_flg, compid, rec_id, warehouse_id, rec_type) {
        alert(compid);
        alert(rec_id);
        alert(warehouse_id);
        alert(rec_type);
        if (posent_to_supplier_ignore_flg == 'posent_to_supplier_ignore') {
            document.getElementById("tbl_po_sent_supplier").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (posent_to_supplier_ignore_flg == 'posent_to_supplier_ignore') {
                    document.getElementById("tbl_po_sent_supplier").innerHTML = xmlhttp.responseText;
                }

            }
        }

        if (posent_to_supplier_ignore_flg == 'posent_to_supplier_ignore') {
            xmlhttp.open("POST", "po_sent_supplier_save.php?posent_tosupplier_ignore=1&ID=" + compid + "&rec_id=" +
                rec_id + "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        }

        xmlhttp.send();
    }


    //
    -->
    </SCRIPT>
    <div id="fade" class="black_overlay"></div>
    <div id="light_reminder" class="white_content_reminder"></div>
    <?php
    //
    $x = "Select * from companyInfo Where ID = " . $_REQUEST["ID"];
    //echo $x;
    db_b2b();
    $dt_view_res = db_query($x);
    $row = array_shift($dt_view_res);
    $sales_rescue_flg = $row["haveNeed"];
    //

    $po_sent_to_supplier_no = "";
    $sent_to_supplier_user = "";
    $sent_to_supplier_dt = "";
    $sent_to_supplier_ignore = "";
    $sent_to_supplier_ignore_user = "";
    $sent_to_supplier_ignore_dt = "";
    if (isset($_REQUEST["rec_id"])) {
        $water_rec_id = $_REQUEST["rec_id"];
    } else {
        $water_rec_id = $_REQUEST["rec_id_water"];
    }
    //$water_rec_id = $_REQUEST["rec_id_water"];

    $dtt_view_qry = "SELECT * from water_loop_transaction WHERE id = '" . $water_rec_id . "'";
    db();
    $dtt_view_res1 = db_query($dtt_view_qry);
    while ($dtt_view_res = array_shift($dtt_view_res1)) {
        $po_sent_to_supplier_no = $dtt_view_res["po_sent_to_supplier_no"];

        $po_sent_to_supplier_user = $dtt_view_res["po_sent_to_supplier_user"];
        $po_sent_to_supplier_dt = $dtt_view_res["po_sent_to_supplier_dt"];

        $po_sent_to_supplier_ignore = $dtt_view_res["po_sent_to_supplier_ignore"];

        $po_sent_to_supplier_ignore_user = $dtt_view_res["po_sent_to_supplier_ignore_user"];
        $po_sent_to_supplier_ignore_dt = $dtt_view_res["po_sent_to_supplier_ignore_dt"];
    }
    ?>

    <br><br>
    <?php

    $dt_view_qry = "SELECT * from water_loop_transaction WHERE id = '" . $water_rec_id . "'";
    db();
    $dt_view_res = db_query($dt_view_qry);
    $trailer_view_row = array_shift($dt_view_res);

    $trailer_number = $trailer_view_row["pr_trailer"];

    $dt_view_qry = "SELECT * from water_loop_transaction WHERE id = '" . $water_rec_id . "' AND pr_employee != ''";
    db();
    $dt_view_res = db_query($dt_view_qry);
    $num_rows = tep_db_num_rows($dt_view_res);
    if (($num_rows < 1) || ($_GET["water_pickup_req_edit"] == "true")) {
    ?>

    <form action="addpickuprequest_water.php" method="post" name="PR" enctype="multipart/form-data">
        <input type="hidden" name="ID" value="<?php echo $_REQUEST['ID']; ?>" />
        <input type="hidden" name="warehouse_id" value="<?php echo isset($id); ?>" />
        <input type="hidden" name="rec_type" value="<?php echo isset($rec_type); ?>" />
        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
        <input type="hidden" name="rec_id" value="<?php echo $water_rec_id; ?>" />
        <?php if ($_GET["water_pickup_req_edit"] == "true") { ?>
        <input type="hidden" name="updatecrm" value="yes" />
        <?php } ?>

        <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table12">
            <tr align="middle">
                <td bgColor="#c0cdda" colSpan="3">
                    <font size="1">REQUESTED NOT PICKED-UP</font>
                </td>
            </tr>

            <?php
                $dt_view_qry = "SELECT * from water_loop_transaction WHERE id = '" . $water_rec_id . "'";
                db();

                $dt_view_res = db_query($dt_view_qry);
                while ($dt_view_row = array_shift($dt_view_res)) {
                    // $num_rows = tep_db_num_rows($dt_view_res);
                    // if ($num_rows > 0) {
                ?>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 135px" class="style1" align="center">
                    <p align="right">Requested By
                </td>
                <td height="13" class="style1" align="left" colspan="2">


                    <Font size='1' Face="arial">
                        <?php if ($_GET["water_pickup_req_edit"] == "true") { ?>
                        <input type=text name="pr_requestby" value="<?php echo $dt_view_row[" pr_requestby"]; ?>"
                            size="20">
                        <input type="hidden" name="pr_employee_old"
                            value="<?php echo $dt_view_row[" pa_employee"]; ?>" />
                        <input type="hidden" name="pr_date_old" value="<?php echo $dt_view_row[" pr_date"]; ?>" />
                        <input type="hidden" name="pr_requestdate_old" value="<?php echo $dt_view_row[" pr_requestdate"];
                                                                                            ?>" />
                        <input type="hidden" name="pr_pickupdate_old"
                            value="<?php echo $dt_view_row[" pr_pickupdate"]; ?>" />
                        <input type="hidden" name="pr_dock_old" value="<?php echo $dt_view_row[" pr_dock"]; ?>" />
                        <input type=hidden name="pr_trailer_old" value="<?php echo $dt_view_row[" pr_trailer"]; ?>">
                        <?php } else { ?>
                        <input type=text name="pr_requestby" size="20" id="pr_requestby">
                        <?php } ?>
                    </font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 135px" class="style1" align="right">
                    Date of Request</td>

                <td height="13" class="style1" align="left" colspan="2">

                    <Font size='1' Face="arial">
                        <?php if ($_GET["water_pickup_req_edit"] == "true") { ?>
                        <input type=text name="pr_requestdate" value="<?php echo $dt_view_row[" pr_requestdate"]; ?>"
                            size="20"> <a href="#"
                            onclick="cal2xx.select(document.PR.pr_requestdate,'anchor2xx','MM/dd/yyyy'); return false;"
                            name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>

                        <?php } else { ?>
                        <input type=text name="pr_requestdate" size="20"> <a href="#"
                            onclick="cal2xx.select(document.PR.pr_requestdate,'anchor2xx','MM/dd/yyyy'); return false;"
                            name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
                        <?php } ?>
                    </font>
                    <div ID="listdiv"
                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 135px" class="style1" align="right">
                    Requested Pickup Date</td>
                <td height="13" class="style1" align="left" colspan="2">
                    <Font size='1' Face="arial">
                        <?php if ($_GET["water_pickup_req_edit"] == "true") { ?>
                        <input type=text name="pr_pickupdate" value="<?php echo $dt_view_row[" pr_pickupdate"]; ?>"
                            size="20"> <a href="#"
                            onclick="cal3xx.select(document.PR.pr_pickupdate,'anchor3xx','MM/dd/yyyy'); return false;"
                            name="anchor3xx" id="anchor3xx"><img border="0" src="images/calendar.jpg"></a>
                        <?php } else { ?>
                        <input type=text name="pr_pickupdate" size="20"> <a href="#"
                            onclick="cal3xx.select(document.PR.pr_pickupdate,'anchor3xx','MM/dd/yyyy'); return false;"
                            name="anchor3xx" id="anchor3xx"><img border="0" src="images/calendar.jpg"></a>
                        <?php } ?>
                    </font>
                    <div ID="listdiv"
                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
                </td>
            </tr>


            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 135px" class="style1" align="right">
                    Trailer #</td>
                <td height="13" class="style1" align="left" colspan="2">
                    <Font size='1' Face="arial">
                        <?php if ($_GET["water_pickup_req_edit"] == "true") { ?>
                        <input type=text name="pr_trailer" value="<?php echo $dt_view_row[" pr_trailer"]; ?>" size="20">
                        <?php } else { ?>
                        <input type=text name="pr_trailer" size="20">
                        <?php } ?>
                    </font>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 135px" class="style1" align="right">
                    BOL</td>
                <td height="13" class="style1" align="left" colspan="2">

                    <Font size='1' Face="arial">
                        <?php if ($_GET["water_pickup_req_edit"] == "true") { ?>
                        <input type="file" name="bol_file_upload" id="bol_file_upload" value="Upload">
                        <input type="hidden" name="bol_filename" id="bol_filename" value="<?php echo $dt_view_row["
                            bol_filename"]; ?>">
                        <br><a href="files/<?php echo $dt_view_row[" bol_filename"]; ?>" target="_blank">View BOL from
                            dashboard</a>
                        <?php
                                } else {
                                    if ($dt_view_row["bol_filename"] == "") {
                                    ?>
                        <input type="file" name="bol_file_upload" id="bol_file_upload" value="Upload">
                        <?php
                                    } else {
                                    ?>
                        <input type="hidden" name="bol_filename" id="bol_filename" value="<?php echo $dt_view_row["
                            bol_filename"]; ?>">
                        <a href="files/<?php echo $dt_view_row[" bol_filename"]; ?>" target="_blank">View BOL from
                            dashboard</a>
                        <?php
                                    }
                                }
                                ?>
                    </font>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td height="10" style="width: 135px" class="style1" align="right">
                </td>
                <td height="10" style="width: 168px" class="style1" align="center">
                    <input type=submit value="Submit" style="cursor:pointer;">
                </td>
                <td align="center" height="10" style="width: 132px" class="style1">
                    <?php if ($_GET["water_pickup_req_edit"] == "true") { ?>
                    <font size="1" Face="arial"><a href="javascript: window.history.go(-1)">Ignore</a></font>
                    <?php } ?>
                    <?php if ($_GET["water_pickup_req_edit"] != "true") { ?><a href="JavaScript:void(0);"
                        onClick="return ChgTextPR()">No Request</a>
</font>
<?php } ?>
</td>
</tr>
</table>
<?php } ?>
</form>
<?php } ?>

<?php

$dt_view_qry = "SELECT * from water_loop_transaction WHERE id = '" . $water_rec_id . "' AND pr_employee != ''";
db();
$dt_view_res = db_query($dt_view_qry);
$num_rows = tep_db_num_rows($dt_view_res);
if ($num_rows > 0) {
?>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table12">
    <tr align="middle">
        <td bgColor="#c0cdda" colSpan="3">
            <font size="1">REQUESTED NOT PICKED-UP</font>
            <a
                href="viewCompany_func_water-mysqli.php?ID=<?php echo $_REQUEST['ID']; ?>&show=watertransactions&warehouse_id=<?php echo isset($id); ?>&b2bid=<?php echo $_REQUEST['ID']; ?>&rec_id=<?php echo $water_rec_id; ?>&rec_type=&proc=View&searchcrit=&display=water_sort_pickup&water_pickup_req_edit=true">EDIT</a>
        </td>
    </tr>

    <?php
        while ($dt_view_row = array_shift($dt_view_res)) {
            $pr_send_eml_ignore = $dt_view_row["pr_send_eml_ignore"];
            $pr_send_eml_date_time = $dt_view_row["pr_send_eml_date_time"];
        ?>
    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 135px" class="style1" align="center">
            <p align="right">Requested By
        </td>
        <td height="13" class="style1" align="left" colspan="2">
            <Font size='1' Face="arial">

                <?php echo $dt_view_row["pr_requestby"]; ?>
            </font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 135px" class="style1" align="right">
            Date of Request</td>
        <td height="13" class="style1" align="left" colspan="2">
            <font size='1' Face="arial">
                <?php echo $dt_view_row["pr_requestdate"]; ?>
            </font>
        </td>
    </tr>

    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 135px" class="style1" align="right">
            Requested Pickup Date</td>
        <td height="13" class="style1" align="left" colspan="2">
            <font size='1' Face="arial">
                <?php echo $dt_view_row["pr_pickupdate"]; ?>
            </font>
        </td>
    </tr>


    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 135px" class="style1" align="right">
            Trailer #</td>
        <td height="13" class="style1" align="left" colspan="2">
            <font size='1' Face="arial">
                <?php echo $dt_view_row["pr_trailer"]; ?>
            </font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 135px" class="style1" align="right">
            BOL</td>

        <td height="13" class="style1" align="left" colspan="2">
            <Font size='1' Face="arial">
                <a href="files/<?php echo $dt_view_row[" bol_filename"]; ?>" target="_blank">View BOL from dashboard</a>
            </font>
        </td>
    </tr>

    </td>
    </tr>
    <?php } ?>

</table>

<?php } ?>

<?php

$dt_view_qry = "SELECT * from water_loop_transaction WHERE id = '" . $water_rec_id . "' ";
db();
$dt_view_res = db_query($dt_view_qry);
$num_rows = tep_db_num_rows($dt_view_res);
if (($num_rows < 1) || ($_GET["water_pickup_confirm_edit"] == "true")) {

?>

<form action="addpickuparrangement_water.php" method="post" name="PA">
    <input type="hidden" name="warehouse_id" value="<?php echo isset($id); ?>" />
    <input type="hidden" name="ID" value="<?php echo $_REQUEST['ID']; ?>" />
    <input type="hidden" name="rec_type" value="<?php echo isset($rec_type); ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $water_rec_id; ?>" />
    <!--<input type="hidden" name="recipient" value="<?php //echo $warehouse_contact_email; 
                                                            ?>"/>-->
    <?php if ($_GET["water_pickup_confirm_edit"] == "true") { ?>
    <input type="hidden" name="updatecrm" value="yes" />
    <input type="hidden" name="updated" value="yes" />
    <?php } ?>



    <br><br>

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table13">
        <tr align="middle">
            <td bgColor="#c0cdda" colSpan="3">
                <font size="1">CONFIRM IT'S PICKED-UP - NEEDS WATER INVOICE NUMBER</font>
            </td>
        </tr>
        <?php

            $dt_view_qry = "SELECT * from water_loop_transaction WHERE id = " . $water_rec_id;
            //echo $dt_view_qry;
            db();
            $dt_view_res = db_query($dt_view_qry);
            while ($dt_view_row = array_shift($dt_view_res)) {

                if ($dt_view_row["pa_delivery_date"] != "") {
                    $delivery_date = date('m/d/Y', strtotime($dt_view_row["pa_delivery_date"]));
                } else {
                    $delivery_date = '';
                }
                // $num_rows = tep_db_num_rows($dt_view_res);
                // if ($num_rows > 0) {
            ?>



        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 150px" class="style1" align="right">
                Confirm Pickup</td>

            <td height="13" class="style1" align="left" colspan="2">
                <input type="checkbox" name="cnfmPickup" value="Confirmed">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 86px" class="style1" align="right">Pickup Date</td>
            <td height="13" class="style1" align="left" colspan="2">
                <Font size='1' Face="arial">
                    <?php if ($_GET["water_pickup_confirm_edit"] == "true") {
                                if ($dt_view_row["pa_pickupdate"] == "") {
                            ?>
                    <input type=text name="pa_pickupdate" value="<?php echo $dt_view_row[" pr_requestdate"]; ?>"
                        size="11">
                    <?php
                                } else {
                                ?>
                    <input type=text name="pa_pickupdate" value="<?php echo $dt_view_row[" pa_pickupdate"]; ?>"
                        size="11">
                    <?php
                                }
                                ?>
                    <a href="#"
                        onclick="cal4xx.select(document.PA.pa_pickupdate,'anchor4xx','MM/dd/yyyy'); return false;"
                        name="anchor4xx" id="anchor4xx"><img border="0" src="images/calendar.jpg"></a>
                    <input type="hidden" name="pa_employee_old" value="<?php echo $dt_view_row[" pa_employee"]; ?>" />
                    <input type="hidden" name="pa_vendor_old" value="<?php echo $dt_view_row[" pa_vendor"]; ?>" />
                    <input type=hidden name="pa_date_old" value="<?php echo $dt_view_row[" pa_date"]; ?>">
                    <?php } else { ?>
                    <?php if ($dt_view_row["pr_requestdate"] != "0000-00-00 00:00:00" || $dt_view_row["pr_requestdate"] != "") {
                                    $reqdate = $dt_view_row["pr_requestdate"];
                                } else {
                                    $reqdate = "";
                                }
                                ?>
                    <input type="text" name="pa_pickupdate" size="11" value="<?php echo $reqdate; ?>"> <a href="#"
                        onclick="cal4xx.select(document.PA.pa_pickupdate,'anchor4xx','MM/dd/yyyy'); return false;"
                        name="anchor4xx" id="anchor4xx"><img border="0" src="images/calendar.jpg"></a>
                    <?php } ?>
                </font>
                <div ID="listdiv"
                    STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                </div>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="10" style="width: 86px" class="style1" align="right">
            </td>
            <td height="10" style="width: 216px" class="style1" align="center">
                <input type=submit value="Submit" style="cursor:pointer;">
            </td>
            <td align="center" height="10" style="width: 132px" class="style1">
                <?php if ($_GET["water_pickup_confirm_edit"] == "true") { ?>
                <font size="1" Face="arial"><a href="javascript: window.history.go(-1)">Ignore</a>
                    <?php } ?>
                    <?php if ($_GET["water_pickup_confirm_edit"] != "true") { ?><a
                        href="javascript:void goToOptionPA(document.PA.pa_vendor,'No Pickup Arrangement'); goToOptionPAA(document.PA.pa_warehouse,'No Delivery Warehouse')">No
                        Arrangement</a>
                    <?php } ?>
                </font>
            </td>
        </tr>


    </table>

    <?php } ?>
</form>
<?php } ?>

<br>
<?php

$dt_view_qry = "SELECT * from water_loop_transaction WHERE id = '" . $water_rec_id . "' ";
db();
$dt_view_res = db_query($dt_view_qry);

$num_rows = tep_db_num_rows($dt_view_res);

if ($num_rows > 0) {

?>



<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table13">
    <tr align="middle">
        <td bgColor="#c0cdda" colSpan="3">
            <font size="1">CONFIRM IT'S PICKED-UP - NEEDS WATER INVOICE NUMBER <br>TRAILER #
                <?php echo $trailer_number; ?>
            </font>
            <a
                href="viewCompany_func_water-mysqli.php?ID=<?php echo $_REQUEST['ID']; ?>&show=watertransactions&warehouse_id=<?php echo isset($id); ?>&b2bid=<?php echo $_REQUEST['ID']; ?>&rec_id=<?php echo $water_rec_id; ?>&rec_type=&proc=View&searchcrit=&display=water_sort_pickup&water_pickup_confirm_edit=true">EDIT</a>
        </td>
    </tr>
    <?php
        while ($dt_view_row = array_shift($dt_view_res)) {

            if ($dt_view_row["pa_delivery_date"] != "") {
                $delivery_date = date('m/d/Y', strtotime($dt_view_row["pa_delivery_date"]));
            } else {
                $delivery_date = '';
            }
        ?>



    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Confirm Pickup</td>
        <td height="13" class="style1" align="left" colspan="2">
            <Font size='1' Face="arial">
                <?php echo $dt_view_row["cnfmPickup"]; ?>
            </font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">Pickup Date</td>
        <td height="13" class="style1" align="left" colspan="2">
            <Font size='1' Face="arial">
                <?php echo $dt_view_row["pa_pickupdate"]; ?>
            </font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Pick up Confirmed by</td>
        <td height="13" class="style1" align="left" colspan="2">
            <?php echo $dt_view_row["pa_employee"]; ?>
        </td>
    </tr>

    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Confirmed by Email</td>
        <td height="13" class="style1" align="left" colspan="2">
            <?php echo $dt_view_row["confirmed_by_email"]; ?>
        </td>
    </tr>

    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Date Entered</td>
        <td height="13" class="style1" align="left" colspan="2">
            <?php echo $dt_view_row["pa_date"]; ?>
        </td>
    </tr>



    <?php } ?>
</table>

<?php } ?>

<br>
<?php
$dt_view_qry = "SELECT * from water_loop_transaction WHERE id = '" . $water_rec_id . "' AND cp_notes != ''";
db();
$dt_view_res = db_query($dt_view_qry);
$num_rows = tep_db_num_rows($dt_view_res);
if (($num_rows < 1) || ($_GET["water_pickup_report_edit"] == "true")) {
?>


<form action="addconfirmpickup_water.php" method="post" name="CP" id="CP">
    <input type="hidden" name="ID" value="<?php echo $_REQUEST['ID']; ?>" />
    <input type="hidden" name="warehouse_id" value="<?php echo isset($id); ?>" />
    <input type="hidden" name="rec_type" value="<?php echo isset($rec_type); ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $water_rec_id; ?>" />
    <?php if ($_GET["water_pickup_report_edit"] == "true") { ?>
    <input type="hidden" name="updatecrm" value="yes" />
    <?php } ?>
    <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table14">
        <tr align="middle">
            <td bgColor="#c0cdda" colSpan="3">
                <font size="1">PICKED-UP AND REPORTED IN WATER WITH INVOICE NUMBER</font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 85px" class="style1" align="center">
                <p align="right">Invoice Number
            </td>
            <td height="13" class="style1" align="left" colspan="2">
                <Font size='1' Face="arial">
                    <?php if ($_GET["water_pickup_report_edit"] == "true") {
                            while ($dt_view_row = array_shift($dt_view_res)) {
                        ?>
                    <textarea id="cp_notes" name="cp_notes"><?php echo $dt_view_row["cp_notes"]; ?></textarea>
                    <input type="hidden" name="old_cp_notes" value="<?php echo $dt_view_row[" cp_notes"]; ?>">
                    <input type="hidden" name="old_cp_employee" value="<?php echo $dt_view_row[" cp_employee"]; ?>">
                    <input type="hidden" name="old_cp_date" value="<?php echo $dt_view_row[" cp_date"]; ?>">
                    <?php }
                        } else { ?>
                    <textarea name="cp_notes" id="cp_notes"></textarea>
                </font>
            </td>
            <?php } ?>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="10" style="width: 85px" class="style1" align="right">
            </td>
            <td height="10" style="width: 217px" class="style1" align="center">
                <input type=submit value="Submit" style="cursor:pointer;">
            </td>
            <td align="center" height="10" style="width: 132px" class="style1">
                <?php if ($_GET["water_pickup_report_edit"] == "true") { ?>
                <font size="1" Face="arial"><a href="javascript: window.history.go(-1)">Ignore</a>
                    <?php } ?>
                    <?php if ($_GET["water_pickup_report_edit"] != "true") { ?><a href="JavaScript:void(0);"
                        onClick="return ChgTextCP()">No Confirmation</a>
                    <?php } ?>
                    <font size="1" Face="arial">&nbsp;</font>
            </td>
        </tr>
    </table>
</form>
<?php } ?>

<br>

<?php

$dt_view_qry = "SELECT * from water_loop_transaction WHERE id = '" . $water_rec_id . "' AND cp_notes != ''";
$dt_view_res = db_query($dt_view_qry);
$num_rows = tep_db_num_rows($dt_view_res);
if ($num_rows > 0) {
?>



<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table13">
    <tr align="middle">
        <td bgColor="#c0cdda" colSpan="3">
            <font size="1">PICKED-UP AND REPORTED IN WATER WITH INVOICE NUMBER <br>
                TRAILER #
                <?php echo $trailer_number; ?>
            </font>
            <a
                href="viewCompany_func_water-mysqli.php?ID=<?php echo $_REQUEST['ID']; ?>&show=watertransactions&warehouse_id=<?php echo isset($id); ?>&b2bid=<?php echo $_REQUEST['ID']; ?>&rec_id=<?php echo $water_rec_id; ?>&rec_type=&proc=View&searchcrit=&display=water_sort_pickup&water_pickup_report_edit=true">EDIT</a>
        </td>

    </tr>


    <?php
        while ($dt_view_row = array_shift($dt_view_res)) {
        ?>


    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Invoice Number</td>
        <td height="13" class="style1" align="left" colspan="2">

            <?php echo $dt_view_row["cp_notes"]; ?>
        </td>
    </tr>

    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Employee</td>
        <td height="13" class="style1" align="left" colspan="2">

            <?php echo $dt_view_row["cp_employee"]; ?>
        </td>
    </tr>

    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 86px" class="style1" align="left">
            Date Entered</td>
        <td height="13" class="style1" align="left" colspan="2">

            <?php echo $dt_view_row["cp_date"]; ?>
        </td>
    </tr>



    <?php } ?>
</table>
<?php } ?>

<br>


</font>
</font>

</font>
</font>