<style>
    select.select_box_fix_width {
        width: 12%;
        overflow-x: auto;
    }
</style>
<?php
// ini_set("display_errors", "1");

// error_reporting(E_ERROR);
session_start();
if ($_REQUEST["no_sess"] == "yes") {
} else {
    require("inc/header_session.php");
}

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
require("inc/functions_mysqli.php");
// require("function-dashboard-newlinks.php");
require("leadertbl_sales_quota_history.php");


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
$user_lvl = $row['level'];
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Todo List</title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title><?php echo $initials; ?> - Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
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
        function showcontact_details(compid, search_keyword) {
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

            xmlhttp.open("GET", "dashboard-search-contact.php?compid=" + compid + "&search_keyword=" + encodeURIComponent(
                search_keyword), true);
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
            document.getElementById('light').style.width = '900px';
            document.getElementById('light').style.top = elementTop + 100 + 'px';
        }

        function load_div_dash_purchasing(id) {
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

            document.getElementById('light').style.width = '1100px';
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

    <style>
        .onlytext-link {
            FONT-WEIGHT: bold;
            FONT-SIZE: 8pt;
            COLOR: 006600;
            FONT-FAMILY: Arial;
        }

        table.newlinks tr:nth-child(even) {
            background-color: #e4e4e4;
        }

        table.newlinks tr:nth-child(odd) {
            background-color: #F7F7F7;
        }

        table.newlinks tr td.style12 {
            text-align: left !important;
        }

        .style24 {
            font-size: 14px;
            font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
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
                //xmlhttp.open("POST","showStatusesDashboard.php?viewin="+viewin+"&eid="+eid+"&show_number="+show_number+"&dtrange="+dtrange,true);
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


        function ex_today_snapshot(initials, dashboard_view) {
            var display_div = "ex_today_snapshot_div";

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
                            document.getElementById("span_today_snapshot").style.display = "none";
                            document.getElementById(display_div).innerHTML = xmlhttp.responseText;
                        }
                    }
                }

                xmlhttp.open("POST", "dash_today_snapshot.php?initials=" + initials + "&dashboard_view=" + dashboard_view,
                    true);
                xmlhttp.send();
            } else {
                document.getElementById("span_close_deal_pipline").style.display = "none";
                document.getElementById(display_div).style.display = "block";
            }
        }

        function colp_today_snapshot() {
            var display_div = "ex_today_snapshot_div";
            document.getElementById(display_div).style.display = "none";
            document.getElementById("span_today_snapshot").style.display = "block";
        }


        function ex_activity_tracking(initials, dashboard_view) {
            var display_div = "activity_tracking_div";

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
                            document.getElementById("span_activity_tracking").style.display = "none";
                            document.getElementById(display_div).innerHTML = xmlhttp.responseText;
                        }
                    }
                }

                xmlhttp.open("POST", "dash_activity_tracking.php?initials=" + initials + "&dashboard_view=" +
                    dashboard_view, true);
                xmlhttp.send();
            } else {
                document.getElementById("span_activity_tracking").style.display = "none";
                document.getElementById(display_div).style.display = "block";
            }
        }

        function colp_activity_tracking() {
            var display_div = "activity_tracking_div";
            document.getElementById(display_div).style.display = "none";
            document.getElementById("span_activity_tracking").style.display = "block";
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

        function ex_close_deal_pipline_sourcing(initials, dashboard_view) {
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

                xmlhttp.open("POST", "dash_closed_deal_pipeline_sourcing.php?initials=" + initials + "&dashboard_view=" +
                    dashboard_view, true);
                xmlhttp.send();
            } else {
                document.getElementById("span_close_deal_pipline").style.display = "none";
                document.getElementById(display_div).style.display = "block";
            }
        }

        function colp_close_deal_pipline_sourcing() {
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

                if (dashboard_view == "Rescue") {
                    xmlhttp.open("POST", "dash_revenue_tracker_period_new.php?initials=" + initials + "&dashboard_view=" +
                        dashboard_view, true);
                } else {
                    //xmlhttp.open("POST","dash_revenue_tracker.php?initials="+initials+"&dashboard_view="+dashboard_view,true);
                    xmlhttp.open("POST", "dash_revenue_tracker_new.php?initials=" + initials + "&dashboard_view=" +
                        dashboard_view, true);
                }
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

        function ex_dash_po_enter_rescue(st_date, end_date, po_key, emp_initial, dashboardview) {
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

                xmlhttp.open("POST", "dash_po_entered_rescue.php?st_date=" + st_date + "&end_date=" + end_date +
                    "&po_key=" + po_key + "&emp_initial=" + emp_initial + "&dashboardview=" + dashboardview, true);
                xmlhttp.send();
            } else {
                document.getElementById("hide_tr").style.display = "none";
                document.getElementById(display_div).style.display = "block";
            }
        }

        function colp_dash_po_enter_rescue(po_key) {
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
    <?php include("inc/header.php"); ?>




    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                B2B Dashboard Homepage
            </div>

        </div>


        <div style="height: 20px;">&nbsp;</div>

        <table border="0" width="1600">
            <tbody>
                <tr>
                    <td></td>
                    <td align="left" height="10"></td>
                </tr>


                <tr style="padding-bottom:1px">
                    <td width="200" valign="top" style="padding-bottom:1px" border="1px">
                        <font size="2">
                            <a href="dashboardnew_todo.php">Tasks (<font color="red">0</font>, <font color="#4b9952">0
                                </font>, <font color="black">0</font>)</a><br><br>

                            <a href="dashboardnew_opportunity.php">Opportunities</a><br><br>
                            <a href="dashboardnew_account_pipeline.php"><b>Account Pipeline</b></a><br><br>
                            <a href="dashboard_inventory_v3.php">All Inventory Available to Sell v3.0</a><br><br>
                            <a href="dashboard_sales_quotas.php?initials=<?php echo urlencode($row['initials']); ?>">Sales
                                Quota History</a><br><br>
                            <a href="dashboard_commissions.php">Commissions</a><br><br>
                            <a href="function-dashboard-newlinks.php"><b>Old Dashboard Links</b></a><br><br>

                        </font>
                    </td>
                    <td width="1200" valign="top">

                        <?php
                        function useful_links_new2021(): void
                        {

                        ?>
                            <?php
                            $rec_found = "super_no";
                            $user_qry = "SELECT id from loop_employees where level = 2 and initials = '" . $_COOKIE['userinitials'] . "'";
                            db();
                            $user_res = db_query($user_qry);
                            while ($user_row = array_shift($user_res)) {
                                $rec_found = "super_yes";
                            }

                            ?>
                            <table>
                                <tr>
                                    <td valign="top">
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px" align="center">
                                                    <strong>B2B Sales</strong>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://business.usedcardboardboxes.com/gaylord_buy_internal.php?internal=yes&uid=<?php echo $_COOKIE['b2b_id']; ?>">LANDING
                                                        PAGE - Create New B2B Sales Record - Gaylords</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This landing
                                                            page allows the user to create a new company record.</span>
                                                    </div>

                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://business.usedcardboardboxes.com/bulk_buy_internal.php?internal=yes&uid=<?php echo $_COOKIE['b2b_id']; ?>">
                                                        LANDING PAGE - Create New B2B Sales Record - Shipping Boxes</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This landing
                                                            page allows the user to create a new company record.</span>
                                                    </div>

                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://business.usedcardboardboxes.com/gaylord_sell_internal.php?internal=yes&uid=<?php echo $_COOKIE['b2b_id']; ?>">
                                                        LANDING PAGE - Create New B2B Purchasing Record - Gaylords</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This landing
                                                            page allows the user to create a new company record.</span>
                                                    </div>

                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://business.usedcardboardboxes.com/bulk_sell_internal.php?internal=yes&uid=<?php echo $_COOKIE['b2b_id']; ?>">
                                                        LANDING PAGE - Create New B2B Purchasing Record - Shipping Boxes</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This landing
                                                            page allows the user to create a new company record.</span>
                                                    </div>

                                                </td>
                                            </tr>
                                            <?php
                                            if ($rec_found == "super_yes") {
                                            ?>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_UCB_leaderboard.php">
                                                            UCB Leaderboard Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user UCB sales data for all departments, including a
                                                                date range selector a the top.</span></div>

                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_daily_chart_mgmt_ver2.php">
                                                        B2B Leaderboard Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user UCB's B2B sourcing and sales data, itemized by
                                                            employee and time frame, including a date range selector a the
                                                            top. This report also has child reports to be able to see all
                                                            transactions and what step in the process they are, the number
                                                            of new deals closed by each rep, and an account ownership
                                                            breakdown.</span></div>
                                                </td>
                                            </tr>


                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_daily_chart_mgmt_ucbzw_gprofit.php">
                                                        UCBZW Share Review Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user UCBZW share data itemized by employee and time
                                                            frame, including a date range selector a the top. </span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_daily_chart_mgmt_pallet.php">
                                                        Pallet Leaderboard Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user UCB's Pallet sourcing and sales data, itemized by
                                                            employee and time frame, including a date range selector a the
                                                            top. This report also has child reports to be able to see all
                                                            transactions and what step in the process they are, the number
                                                            of new deals closed by each rep, and an account ownership
                                                            breakdown.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_sold_buy.php">
                                                        Customer Report (>90 days <180 days)</a>
                                                            <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This
                                                                    report shows all B2B customers who have last purchased
                                                                    from UCB with 90 and 180 days ago.</span></div>

                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_all_emp_first12month_lead.php">
                                                        Sales Rep's Revenue in 1st Year Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user the amount of revenue each sales rep has sold
                                                            within their first year (12 months) of employment.</span></div>

                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_boxesselltoucb_reminder.php">
                                                        >90 day from last load, Purchasing Records Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B Purchasing suppliers (meaning they have
                                                            sold to UCB before) who are overdue for a load sold to UCB
                                                            because the average time between their previous loads is longer
                                                            than the time since their last load we purchased, AND they have
                                                            not been contacted in the last 3 months.</span></div>

                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_boxesorder_reminder.php">
                                                        >90 day from last order, Sales Records Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This report shows the user all B2B Sales
                                                            customers (meaning they have purchased from UCB before) who are
                                                            overdue for an order because the average time between their
                                                            previous orders is longer than the time since their last order,
                                                            AND they have not been contacted in the last 3 months.' to 'This
                                                            report shows the user all B2B Sales customers (meaning they have
                                                            purchased from UCB before) who are overdue for an order for
                                                            various reasons such as the average time between their previous
                                                            orders is longer than the time since their last order, or having
                                                            no orders in a certain amount of time.</span>
                                                    </div>

                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_account_manager_summary.php">
                                                        B2B Sales Rep Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user everything you need to know about a B2B Sales Rep
                                                            regarding deals they sell, quotas, largest customers,
                                                            etc.</span></div>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">

                                                    <a target="_blank" href="name_dropping_report.php">
                                                        Name Dropping Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the biggest companies in each industry
                                                            that we sell to, so that when we go to sell to other companies
                                                            in that industry, we can drop the names of the big ones.</span>
                                                    </div>

                                                </td>
                                            </tr>
                                            <?php
                                            if ($rec_found == "super_yes") {
                                            ?>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_profit_analysis.php">
                                                            UCB Profit Analysis Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user the profitability of each department by month.
                                                                This report will not match QuickBooks directly, but will be a
                                                                good estimation of gross profit by department.</span></div>

                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            <!--<tr vAlign="center"> 
																		<td class="style12" >
																			<a target="_blank" href="report_UCB_leaderboard.php">
																				Sales Leaderboard Report</a> <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report shows the user UCB sales data for all departments, itemized by employee and time frame, including a date range selector a the top. This report also has child reports to be able to see all transactions and what step in the process they are, the number of new deals closed by each rep, and an account ownership breakdown.</span></div>

																		</td> 
																	</tr> -->
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="special_ops_report.php">
                                                        Special Ops Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all special opportunities (special ops) for B2B
                                                            Sales and B2B Purchasing departments. For a proper explanation
                                                            of what "Special Ops" is, and what defines an account as such,
                                                            open the What is SpecOps SOP.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_mgmt_closed_deal_pipeline_summary.php">
                                                        Closed Deal Pipeline Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all outstanding B2B sales transactions and what
                                                            part of the order-to-cash cycle they are in. Every transaction
                                                            entered starts at the left, and works itself to the right in the
                                                            process sequentially.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_mgmt_new_deal_spins.php">
                                                        New Deal Spin Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the new deals closed and paid in full by
                                                            customers. The report will then convert all of the deals into
                                                            spins based on order size. Only 1st transactions counts for each
                                                            customer.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_mgmt_activity_tracking.php">
                                                        Contact Activity Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the contact activity made by each B2B
                                                            sales rep.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_mgmt_sales_assignments.php">
                                                        Sales Account Ownership Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B sales records in the system summarized by
                                                            account owner.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_mgmt_rescue_assignments.php">
                                                        Purchasing Account Ownership Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B purchasing records in the system
                                                            summarized by account owner.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_mgmt_other_assignments.php">
                                                        Other Account Type Ownership Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B records in the system not classified as
                                                            B2B Sales or B2B Purchasing records, summarized by account
                                                            owner.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_industry_penetration_list.php">
                                                        Industry Penetration Report - Sales Records</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the largest companies within each industry
                                                            which generally is qualified to buy from UCB (Sales Records). If
                                                            you are looking for industries which sells to UCB, then use the
                                                            Industry Penetrations Report - Purchasing Records.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_rescue_industry_penetration_list.php">
                                                        Industry Penetration Report - Purchasing Records</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the largest companies within each industry
                                                            which generally is qualified to sell to UCB (Purchasing
                                                            Records). If you are looking for industries which buy from UCB,
                                                            then use the Industry Penetrations Report - Sales
                                                            Records.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="matchGaylordNEW_csv.php">
                                                        Inventory Alert Email List Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all companies that are within a certain
                                                            range of an inventory item, which is used to generate email
                                                            lists for marketing inventory alerts.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_demand_alert_email.php">
                                                        Demand Alert Email List Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all companies that are within a certain
                                                            range of a demand entry, which is used to generate email lists
                                                            for marketing demand alerts.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="getEmails2.php">
                                                        B2B Email List Tool</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                            allows the user to generate email lists based on filtering
                                                            criteria.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="Report_CRM_older_than3months.php">
                                                        Red Line Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This report allows the user to see all
                                                            accounts which haven't been contacted in over 3 months, as well
                                                            as those that are on the verge of re-assignment due to
                                                            mismanagement after 6 months.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_inactive_emp.php">
                                                        Accounts Assigned to Inactive Reps Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all company records (accounts) which are
                                                            still assigned to an inactive account owner.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_multiple_status.php">
                                                        Accounts Owned by Status Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all company records (accounts) which are
                                                            filters by account status.</span></div>

                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_boxesorder_reminder.php">
                                                        Client Retention Report - B2B Sales</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B Sales customers (meaning they have
                                                            purchased from UCB before) who are overdue for an order because
                                                            the average time between their previous orders is longer than
                                                            the time since their last order, AND they have not been
                                                            contacted in the last 3 months.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_boxesselltoucb_reminder.php">
                                                        Client Retention Report - B2B Purchasing</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B Purchasing suppliers (meaning they have
                                                            sold to UCB before) who are overdue for a load sold to UCB
                                                            because the average time between their previous loads is longer
                                                            than the time since their last load we purchased, AND they have
                                                            not been contacted in the last 3 months.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_show_unassign_lead.php">
                                                        Accounts with No Account Owner Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all company records (accounts) which are
                                                            still assigned to an inactive account owner.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_lead_tracking.php">
                                                        B2B Lead Tracking Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all company records by the date they were
                                                            entered into the system, and seeing where they are at in the
                                                            sales and purchasing processes respectively.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_sales_team_list.php">
                                                        Call List Report - B2B Sales</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B sales accounts in the system which meet
                                                            the conditions listed. These conditions are designed to generate
                                                            a focused report of accounts which should have a higher
                                                            probability to yield sales. This list is refreshed every night,
                                                            and then divided up evenly and fairly amongst all sales reps on
                                                            the list.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_purchasing_team_list.php">
                                                        Call List Report - B2B Purchasing</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B purchasing accounts in the system which
                                                            meet the conditions listed. These conditions are designed to
                                                            generate a focused report of accounts which should have a higher
                                                            probability to yield sales. This list is refreshed every night,
                                                            and then divided up evenly and fairly amongst all sales reps on
                                                            the list.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_hide_from_call_list.php">
                                                        Hide from Call List Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows all of the "Hide from Call List" companies</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_transaction_list.php">
                                                        Transactions Sold, Load # & Profit Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the transactions sold within a date range,
                                                            and state which load # it is for that customer (example, their
                                                            first ordered load, their 5th loads, their 87th loads, etc), and
                                                            how much gross profit we made off the transaction (and whether
                                                            the gross profit calculation is finalized or estimated), and who
                                                            sold the load.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_purchasing_rep.php">
                                                        B2B Purchasing Rep Revenue Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all revenue a purchasing rep has produced from
                                                            their deals within a date range.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_supply_demand.php">
                                                        Supply & Demand Scorecard Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This reports
                                                            shows the user a summary of data regarding supply (what we have
                                                            to sell) and demand (what customers want to buy).</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_purchasing_revenue.php">
                                                        Purchasing Revenue & Profit Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows purchasing revenue & profit for selected employee.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="order_fulfillment_new.php">
                                                        Quote Request Summary Tool</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to view a summary of the quote request database
                                                            entries, and mark when quotes have been made, or to deny the
                                                            requests.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="SCORECARD_sales_function_sales_dept.php">
                                                        SCORECARD: B2B Sales Team / Rep</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This scorecard
                                                            shows the user the data for the B2B sales team or an individual
                                                            rep.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_top_ucb_client.php">
                                                        UCB Top Clients Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows UCB's largest (top) customer and suppliers, measured in
                                                            various ways such as revenue, payments, transactions, and
                                                            profit.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="Coupa_csv.php">
                                                        LKQ COUPA Catalog Export Tool</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to create a new csv catalog file with the click
                                                            of a button, which can be used to upload into the COUPA system
                                                            so that COUPA customers know the inventory UCB has
                                                            available.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="Jaggaer_csv.php">
                                                        Schnitzer Steel Catalog Export Tool</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to create a new csv catalog file with the click
                                                            of a button, which can be used to upload into the JAGGAER system
                                                            so that Schnitzer Steel customers know the inventory UCB has
                                                            available to them. As a note, products cannot be uploaded into
                                                            JAGGAER without an ORACLE Part Number.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="heatmap_where_UCBhas_gaylords.php">
                                                        Heat Map: Gaylord Suppliers</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This heat map
                                                            shows the user where all B2B gaylord suppliers are located,
                                                            counted over the lifetime of UCB.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="heatmap_where_customersbuy_gaylords.php">
                                                        Heat Map: Gaylord Customers</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This heat map
                                                            shows the user where all B2B gaylord customers are located,
                                                            counted over the lifetime of UCB.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="heatmap_where_UCBhas_shipping.php">
                                                        Heat Map: Shipping Box Suppliers</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This heat map
                                                            shows the user where all B2B shipping box suppliers are located,
                                                            counted over the lifetime of UCB.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="heatmap_where_customersbuy_shipping.php">
                                                        Heat Map: Shipping Box Customers</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This heat map shows the user where all B2B
                                                            shipping box customers are located, counted over the lifetime of
                                                            UCB.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="heatmap_where_UCBhas_pallets.php">
                                                        Heat Map: Pallet Suppliers</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This heat map shows the user where all B2B
                                                            pallet suppliers are located, counted over the lifetime of
                                                            UCB.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="heatmap_where_customersbuy_pallets.php">
                                                        Heat Map: Pallet Customers</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This heat map shows the user where all B2B
                                                            pallet customers are located, counted over the lifetime of
                                                            UCB.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="heatmap_where_UCBhas_supersacks.php">
                                                        Heat Map: Supersack Suppliers</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This heat map shows the user where all B2B
                                                            supersack suppliers are located, counted over the lifetime of
                                                            UCB.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="heatmap_where_customersbuy_supersacks.php">
                                                        Heat Map: Supersack Customers</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This heat map
                                                            shows the user where all B2B supersack customers are located,
                                                            counted over the lifetime of UCB.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_b2b_supply_summary.php">
                                                        B2B Supply Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all inventory that UCB has available to sell,
                                                            presell, or even the potential to get...and sorts it by the
                                                            annualized potential revenue. Thus, this report helps the user
                                                            see the most valuable inventory items UCB has in it's entire
                                                            supply pipeline.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_b2b_demand_summary.php">
                                                        B2B Demand Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all demand entries that UCB has the opportunity
                                                            sell to, regardless of whether we satiate that demand or
                                                            not...and sections it by the most valuable demand entries to the
                                                            least. Thus, this report helps the user see the most valuable
                                                            demand entries UCB has in it's entire demand pipeline.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_active_task_list.php">
                                                        Active Tasks Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows all currently active tasks per employee.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_quote_purchase_details.php">
                                                        B2B Quotes and Purchase Orders Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This report shows the user all sales
                                                            quotes and purchase orders generated, as well as details about
                                                            each.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="ucb_lead_quota_all.php">
                                                        UCB Department Quota Tool</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                            allows the user to update the UCB department quotas for B2B
                                                            Sales, B2B Purchasing, B2C, GMI and UCBZW. These values are then
                                                            used to populate the UCB Sales Leaderboard. If you are looking
                                                            to update an employee quota, that is done in the Office Employee
                                                            Database.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="SCORECARD_operations_function_demand_MGR.php">
                                                        SCORECARD: B2B Demand</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This scorecard
                                                            shows the user the data for the demand department, which feeds
                                                            what the B2B purchasing team does.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="customer_transaction_report.php">
                                                        Company Travel Proximity Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to search for all companies within a certain
                                                            proximity of a zip code. This is useful for when someone
                                                            travels. The user can see all companies that will be close to
                                                            the zip code entered and it can be easily decided if any visits
                                                            should be made.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://www.mytjx.com/OA_HTML/RF.jsp?function_id=28636&resp_id=-1&resp_appl_id=-1&security_group_id=0&lang_code=US&params=KQ0ueFd3h5ncJDQ0.532EQ&oas=cZcsThSRq-BNoNiB9_hWpQ..">
                                                        My TJX Login (ORACLE)</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the ORACLE login screen for TJX.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="client_matching_tool.php">
                                                        Matching Tools v3.0</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is a TEST
                                                            report where Zac is developing a new matching tool for UCB Sales
                                                            Reps as well as customers.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="manage_project.php">
                                                        Manage Projects</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report is
                                                            where projects are managed.</span></div>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="manage_task.php">
                                                        Manage Tasks</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This place is where all Tasks are
                                                            processed.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="company_without_industry_value.php">
                                                        Company records with missing industry value</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This report shows us all company records
                                                            in the system that don't have an industry value (excluding
                                                            inactive, out of business, or trash records).</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!--//////////////////End B2B Sales links/////////////////////////-->

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                        </table>
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>B2B Order Fulfillment</strong>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="">Loops Homepage</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            homepage of the loops system, which is the system which houses
                                                            all B2B transactions (orders) for both buying and selling of
                                                            inventory items. Each transaction relates to 1 truckload
                                                            movement.</span>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="index.php?linkfrm=dash">
                                                        Active B2B Order Issues Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all active (outstanding) B2B Order Issues.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_search_order_issue.php">Former B2B Order
                                                        Issues Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B Order Issues, outstanding and former,
                                                            based on the date range the user enters. Red rows mean it took
                                                            longer than 5 days to resolve the issue, which is not
                                                            acceptable.</span></div>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="index.php?linkfrm=dash_act_fulfill">Active B2B
                                                        Fulfillment Issues Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all active (outstanding) B2B Fulfillment
                                                            Issues.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_search_fulfillment_issue.php">Former B2B
                                                        Fulfillment Issues Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B Fulfillment Issues, outstanding and
                                                            former, based on the date range the user enters. Red rows mean
                                                            it took longer than 5 days to resolve the issue, which is not
                                                            acceptable.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="index-newlink.php?emp_list=<?php echo $_COOKIE["userinitials"]; ?>&linkfrm=dash_cancelorders">Cancelled
                                                        B2B Transactions Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all cancelled B2B transactions (ignored).</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="index-newlink.php?emp_list=<?php echo $_COOKIE["userinitials"]; ?>&linkfrm=dash_blankopsdeliverydt">Needs
                                                        Ops Delivery Date Updated Tool</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool shows
                                                            the user all B2B transactions where the Ops Delivery Date has
                                                            passed, and allows the user to update it accordingly. Ideally,
                                                            the table would be blank.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_search_b2b_survey.php">B2B Survey
                                                        Response Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to view all responses to B2B surveys, which are
                                                            sent out after each B2B transaction.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_special_order.php">B2B Special Orders
                                                        Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all orders that require special
                                                            processing or re-processing.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="b2becommerce_reports.php">B2B Online Orders
                                                        Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all orders which were placed online. This could
                                                            be from accepting a sales quote, or buying inventory directly
                                                            from an inventory detail page and clicking "Order Now."</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="index-newlink.php?emp_list=<?php echo $_COOKIE["userinitials"]; ?>&linkfrm=dash_blankdockhours">Exception
                                                        Report - Shipping/Receiving Dock Hours</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all company records which have the
                                                            Shipping/Receiving Dock Hours field blank. Ideally, these would
                                                            all be filled in, and thus this report would be blank. If it is
                                                            not blank, we need to contact the customers to determine their
                                                            shipping and receiving dock hours, and then fill in the field
                                                            with the answer. This helps the freight department be effective
                                                            and efficient.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_list_onhold.php">ON HOLD Accounts
                                                        Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all company records which are marked as
                                                            "ON HOLD".</span>
                                                </td>
                                            </tr>

                                            <?php
                                            if ($rec_found == "super_yes") {
                                            ?>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="daviddelete.php">Delete Transaction Tool</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                                allows the user to delete a transaction within a purchasing or
                                                                sales record. This is generally only done if creating the
                                                                transaction was a mistake. Cancelled transactions should use the
                                                                Cancel Transaction Tool.</span>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_ignore.php">Cancel Transaction Tool</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                                allows the user to cancel or re-activate a transaction within a
                                                                purchasing or sales record. This is generally only done if the
                                                                customer cancelled the order. If the transaction was made by
                                                                mistake, then use the Delete Transaction Tool. This used to be
                                                                called the "Ignore" tool. </span>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_del_history.php">Deleted/Cancelled
                                                        Transaction Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B transactions that have been deleted or
                                                            cancelled.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_purchasing_transaction_summary.php?reprun=yes">Purchasing
                                                        Transaction Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2B purchasing transactions within a range
                                                            and filter.</span>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_need_planned_delivery_date_confirmed.php">Customer
                                                        Orders That Need Planned Delivery Date Confirmed by Customer</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the active sales transactions where the
                                                            planned delivery date is not confirmed with the customer. The
                                                            user is to contact the customer (preferrably by phone) and
                                                            confirm the planned delivery date, then update the system that
                                                            they are aware and good with the planned delivery date.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>

                                            <!--//////////////////End Order fulfillment links/////////////////////////-->
                                        </table>
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">

                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>B2B Inventory</strong>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboardnew.php?show=inventory_new">Inventory
                                                        Availability Summary Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all inventory available to sell
                                                            nationwide.</span>
                                                </td>
                                            </tr>
                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboardnew.php?show=inventory_cron">UCB Owned
                                                        Inventory Summary Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all inventory that UCB owns, by sorting
                                                            warehouse.</span>
                                                </td>
                                            </tr>
                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_find_buyers.php">Inventory Item
                                                        Transaction History Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the instances where this inventory item
                                                            was sold, per transaction.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_find_quoters.php">Inventory Item Quoting
                                                        History</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the instances where this inventory item
                                                            was quoted and the status of those quotes.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="gaylordstatus.php">Inventory Availability
                                                        Updater Tool</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">The report
                                                            allows the user to see all inventory items they own the
                                                            relationship of (direct ship only though), and allows them to
                                                            edit the availability of each box. This information will
                                                            populate the sales matching tools for quoting research.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="https://maps.google.com/maps/ms?msid=200896264456963024466.0004c47c2a7b0a8d51fb8&msa=0&ll=49.894634,-95.712891&spn=33.143537,86.572266&iwloc=lyrftr:msid:200896264456963024466.0004c47c2a7b0a8d51fb8,0004c47f23ba253164dff,,,0,-31">Gaylord
                                                        Google Map</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the Gaylord Google map, which shows where all gaylord
                                                            suppliers are nationally for UCB.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="manage_box_b2bloop.php?posting=yes">Inventory
                                                        Specification Database</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This database
                                                            is where all of the data for B2B inventory items are saved. This
                                                            includes the specs, availability, notes, etc.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="manage_sortwh_mrg.php?posting=yes&rec_type=Sorting">Sorting
                                                        Warehouse Database</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This database
                                                            is where all of the data for B2B sorting warehouses are saved.
                                                            Sorting warehouses tells the system WHERE inventory is located
                                                            when we enter a sort report, which thus gets used to display it
                                                            on the inventory screen, and get used to send inventory
                                                            out.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="manage_commodities.php?posting=yes">Commodity
                                                        Values Database</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This database
                                                            is where all of the data for B2B commodity values are saved.
                                                            Commodity values are recycling rates that change over time, and
                                                            are used to automatically update inventory item rebate values
                                                            based on commodity values in different regions.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_inventory_adjustments.php">Inventory
                                                        Adjustments Summary Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to view all manual inventory adjustments made
                                                            within a date range. As a note, inventory adjustments are a LAST
                                                            RESORT regarding inventory management. Making an adjustment
                                                            generally means we were unable to identify the problem, meaning
                                                            there will be a problem lingering somewhere in the system. The
                                                            only exceptions to this are restacking, resizing and/or
                                                            baling.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_inventory_negative.php">Negative Gross
                                                        Profit - Inventory List</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all inventory items where the gross profit stored
                                                            for the item is a negative value. These inventory items should
                                                            be reviewed regularly to ensure they are accurate and not data
                                                            entry mistakes.</span>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="inventory_stock_report.php">
                                                        Inventory Stock Report</a>
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the total B2B cost of all inventory available in
                                                            warehouses.</span></div>
                                                </td>
                                            </tr>

                                            <?php
                                            if ($rec_found == "super_yes") {
                                            ?>
                                                <tr vAlign="middle">
                                                    <td class="style12">
                                                        <a target="_blank" href="inventory_update.php">Manual B2B Inventory
                                                            Adjustment Tool</a>
                                                        &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                                allows the user to move a selected available quantity from one
                                                                inventory SKU to another. Reasons for this include boxes which
                                                                are being restacked to different quantities, inventory which was
                                                                resized to a different height.</span>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="inventory_converter.php">B2B Inventory
                                                        Conversion Tool</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                            allows the user to move a selected available quantity from one
                                                            inventory SKU to another. Reasons for this include boxes which
                                                            are being restacked to different quantities, inventory which was
                                                            resized to a different height.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_inbound_inventory_summary.php">Inventory
                                                        Summary Report - Inbound</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to search the sort report database for all
                                                            inventory received over a period of time, whether by specific
                                                            SKU, by specific sorting warehouse, and/or by a specific company
                                                            UCB buys from.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_Outbound_inventory_summary.php">Inventory Summary
                                                        Report - Outbound</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to search the outbound Bill of Lading (BOL)
                                                            database for all inventory shipped over a period of time,
                                                            whether by specific SKU, by specific sorting warehouse, and/or
                                                            by a specific company UCB sells to.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="tags_details.php">Inventory Tags Summary
                                                        Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to view all tags in the inventory database, and
                                                            all B2B inventory items related to them.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report-inventory-price-changes-summary.php">Inventory Price
                                                        Changes Summary Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to view all changes to pricing and costs over
                                                            time.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_sort_inventory_trailing_30day.php">Warehouse Inventory
                                                        Items w/ No Inbound Flow in Last 30 Days</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all storting warehouse inventory items where the
                                                            trailing 30 days of sort reports is less than or equal to 0.
                                                            This will showcase to the user inventory items we are not
                                                            getting, which may require follow up.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_inventory_items_20_per_profit_margin.php">Inventory
                                                        Items w/ <20% Profit Margin</a>
                                                            &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This
                                                                    report shows the user all sorting warehouse inventory
                                                                    items where profit margin is less than 20%. This will
                                                                    showcase to the user inventory items with below 20%
                                                                    profit margin which may require discussion and fixing
                                                                    the profit margin.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_inventory_prepay.php">Supplier Inventory
                                                        Prepay Active list</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows Supplier Inventory Prepay Active list.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_manage_item_past_date.php">Items with
                                                        Past Due Ship Dates</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            will help Sourcing team to check what are the items that have
                                                            past due delivery dates. In that way they can follow up with
                                                            there respective Suppliers.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_manage_item_have_load_b2b_status_unavailable.php">Unavailable
                                                        Inventory Items with Loads Built</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            list inventory that has Loads but B2b Status is
                                                            unavailable.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="inventory_v1_new.php">Browse Warehouse
                                                        Inventory Tool</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">Browse
                                                            Warehouse Inventory Tool</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_stale_inventory.php">Stale Inventory
                                                        Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">Stale Inventory
                                                            Report</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td class="style12">
                                                    <a target="_blank" href="report_manage_item_future_date.php">Items with
                                                        Future Ship Dates</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">Items with
                                                            Future Ship Dates</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="middle">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>

                                            <!--//////////////////End B2B Inventory links/////////////////////////-->
                                        </table>
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>Freight</strong>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="manage_freightvendor_mrg.php?posting=yes">Freight Vendors
                                                        Database</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This database
                                                            is where all of the data for B2B freight vendors (brokers and
                                                            carriers) are saved. Freight vendors are the companies that
                                                            pickup and deliver our goods, and since UCB does not own our own
                                                            assets (trucks/trailers/drivers), we have to utilize other
                                                            companies.</span>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_freight_broker.php">Freight Vendor
                                                        Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all open lanes booked with freight
                                                            brokers/carriers (vendors) and where they are at in the process.
                                                            This allows the user to understand which lanes need updates so
                                                            we can keep our system updated.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="manual_create_BOL.php">Manually Create a BOL
                                                        Tool</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                            allows the user to create a BOL from scratch.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="SCORECARD_operations_function_freight_MGR.php">SCORECARD:
                                                        Freight Department</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This scorecard
                                                            shows the user the data for the freight department in a date
                                                            range.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://calendly.com/ucbhuntvalley/dock-appointment">Calendly
                                                        - Hunt Valley Processing Facility</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the Calendly system specifically for the Hunt Valley
                                                            Processing Facility, where dock appointments are made for
                                                            carriers/brokers.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://calendly.com/ucbhannibal/dock-appointment-1-hr-window/">Calendly
                                                        - Hannibal Processing Facility</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the Calendly system specifically for the Hannibal
                                                            Processing Facility, where dock appointments are made for
                                                            carriers/brokers.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://calendly.com/ucbmilwaukee/dock-appointment-1-hr-window/">Calendly
                                                        - Milwaukee Processing Facility</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the Calendly system specifically for the Milwaukee
                                                            Processing Facility, where dock appointments are made for
                                                            carriers/brokers.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                            <!--//////////////////End Freight links/////////////////////////-->

                                        </table>
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>Facilities</strong>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_timeclock_hrs_left.php">McCormick HVP
                                                        Staffing Hours Budget Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            calculates the number of hours on the budget for each month, the
                                                            total hours used so far, and the balance remaining. The monthly
                                                            budget of hours can be edited within this report by using the
                                                            "Update Monthly Budget Hours" link.</span>
                                                </td>
                                            </tr>

                                            <?php
                                            if ($rec_found == "super_yes") {
                                            ?>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="Timeclock_Manager.php">Manager Timeclock &
                                                            Production Summary Reports</a>
                                                        &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the users various summaries that relate to the timeclock
                                                                and production within a provided time period.</span>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_timeclockZF.php">Individual Timeclock &
                                                            Production Summary Report</a>
                                                        &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user the summary of the timeclock and production
                                                                within a provided time period for a specific employee. There are
                                                                2 versions of this report, this is the manager version which can
                                                                be edited.</span>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_timeclock_public.php">Employee Public
                                                        Timeclock & Production Summary Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user the summary of the timeclock and production
                                                            within a provided time period for a specific employee.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_timeclock_production_add.php">Add
                                                        Production Tool</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                            allows the user to enter production for employees on the
                                                            timeclock.</span>
                                                </td>
                                            </tr>

                                            <?php
                                            if ($rec_found == "super_yes") {
                                            ?>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="add_bonus.php">Add Other Bonus Tool</a>
                                                        &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                                allows the user to add an additional bonus to their payroll
                                                                summary on a selected date. The list only includes hourly
                                                                employees with an active timeclock profile.</span>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_timeclock_bulk_add.php">
                                                            Bulk Timeclock Entry Add Tool</a>
                                                        &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                                allows the user to add timeclock entries in bulk.</span>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="SCORECARD_operations_function_facilities_dept.php">
                                                        SCORECARD: Facilities Deptartment</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This scorecard
                                                            shows the user the data for the Facilities Department.</span>
                                                </td>
                                            </tr>

                                            <!--<tr vAlign="center"> 
																<td class="style12">
																		<a target="_blank" href="report_timeclock_hrs_left.php">
																		HVP Budget Hours Report</a>
																		&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report shows the user the budget of hours for the HVP staffing program, along with the amount of hours used so far, and the hours remaining. McCormick's fiscal year is from December 1 to November 30, and the budget will need to be re-established before each new fiscal year.</span>
																	</td> 
																</tr>-->

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="production_type_mgmt.php?posting=yes">Production Type
                                                        Database</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This database
                                                            is where all of the data for B2B production values are saved.
                                                            This lists out the types of produciton and their value which
                                                            populates other reports for the facilities department.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="huntvalleywarehouse_159265234358979.php">
                                                        UCB-HV Warehouse Dashboard (Hunt Valley, MD)</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            dashboard report for the UCB Hunt Valley Processing Facility
                                                            (UCB-HV).</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="huntvalleyoffice_159265234358979.php">
                                                        Hunt Valley Office Timeclock</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            online timeclock for the Hunt Valley Office.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="hannibalwarehouse_141592653.php">
                                                        UCB-HA Warehouse Dashboard (Hannibal, MO)</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            dashboard report for the UCB Hannibal Processing Facility
                                                            (UCB-HA).</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="hannibaloffice_141592653.php">
                                                        Hannibal Office Timeclock</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            online timeclock for the Hannibal Office.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="milwaukeeywarehouse_14159265358979.php">
                                                        UCB-ML Warehouse Dashboard (Milwaukee, WI)</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            dashboard report for the UCB Milwaukee Processing Facility
                                                            (UCB-ML).</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="hktranswarehouse_1223644451.php">
                                                        UCB-HK Warehouse Dashboard (Los Angeles, CA)</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            dashboard report for the UCB HK Trans Processing Facility
                                                            (UCB-HK).</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="greenflairrecyclingwarehouse_847441947.php">
                                                        UCB-Green Flair Recycling Warehouse Dashboard (Shelby, NC)</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            dashboard report for the UCB Green Flair Recycling Processing
                                                            Facility.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="integrasupplyportland_5749865412.php">
                                                        UCB - Integra Supply Portland, OR</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            dashboard report for the UCB - Integra Supply Portland, OR
                                                            Facility.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="losangeleswarehouse_23541415243923422653.php">
                                                        Los Angeles Office Timeclock</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            online timeclock for the Los Angeles Office.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                            <!--//////////////////End Facilities links/////////////////////////-->
                                        </table>
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>HR</strong>
                                                </td>
                                            </tr>
                                            <?php
                                            if ($rec_found == "super_yes") {
                                            ?>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="employees_manage_permission.php">
                                                            Office Employee - Permission (Loops)</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                            <span class="tooltiptext">This page helps to set the User level
                                                                Permission.</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="employee.php?posting=yes">Office Employee
                                                        Database (Loops)</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This database
                                                            is where all of the data for office employees with a loops login
                                                            are saved. This is not to be confused with the timeclock
                                                            employee database table. If an employee needs a loops login as
                                                            well as a timeclock, then they will need to be setup in both
                                                            databases.</span>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="loop_worker.php?posting=yes">Employee Timeclock
                                                        Database (Loops)</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This database
                                                            is where all of the data for timeclock employees are saved. This
                                                            is not to be confused with the office employee database table.
                                                            If an employee needs a loops login as well as a timeclock, then
                                                            they will need to be setup in both databases.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://b2c.usedcardboardboxes.com/employee.php?posting=yes">B2C
                                                        Login Setup </a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This database
                                                            is where all of the data for the B2C logins are saved. This is
                                                            not to be confused with the office employee database table for
                                                            loops (different system).</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="Master_Roster.php">Master Roster Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all active employees and contractors at
                                                            UCB.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_sop_new.php">Standard Operating
                                                        Procedure (SOP) Database</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This database
                                                            allows the user to search and view the SOP database entries,
                                                            which are insutrctions on how to do many things at UCB.</span>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://traction.tools/">Traction Tools </a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to Traction Tools, which is the program UCB uses to
                                                            implement EOS.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://wheeldecide.com/index.php?c1=20&c2=40&c3=60&c4=80&c5=100&c6=20&c7=40&c8=60&c9=80&c10=100&c11=20&c12=40&c13=60&c14=80&c15=100&c16=20&c17=40&c18=60&c19=80&c20=100&col=pastel&t=UCB+Small+Prize+Wheel&time=5">SPINS:
                                                        Prize Wheel</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the small prize wheel, used to reward sales and quota
                                                            achievements.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://wheeldecide.com/index.php?c1=10&c2=15&c3=20&c4=25&c5=30&c6=10&c7=15&c8=20&c9=25&c10=30&c11=10&c12=15&c13=20&c14=25&c15=30&c16=10&c17=15&c18=20&c19=25&c20=30&col=wof&t=UCB+PRIZE+WHEEL&time=5 ">SPINS:
                                                        PH Prize Wheel</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the big prize wheel, used to reward sales and quota
                                                            achievements.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://docs.google.com/forms/d/e/1FAIpQLSfeB6f7vRFpBIX-CkjhKtr5QTfR54pZmbfV9BXHBlhXQWVl5w/viewform">Interview
                                                        Evaluation Form</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the interview evaluation form, which is used to provide
                                                            feedback to the recruiting team after each interview about how
                                                            it went.</span></div>
                                                </td>
                                            </tr>



                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                            <!--//////////////////End HR links/////////////////////////-->
                                        </table>
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>Finance</strong>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="seller_updatepayment.php">Update Seller Payment
                                                        in Bulk Tool</a>&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool allows you to update supplier
                                                            payments in bulk. This is an old report and is not generally
                                                            used and is outdated.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="aging_summary_report.php">A/R Aging Summary
                                                        Report</a>&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all companies which are not paid in full as of
                                                            the date selected.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_aging.php">A/R Aging Detail
                                                        Report</a>&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all invoices which are not paid in full as of the
                                                            date selected.</span></div>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_aging_review.php">A/R Aging Review
                                                        Report</a>&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user invoices which are not paid in full as of the
                                                            date selected and only three tables are shown and no Recycling
                                                            category.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_aging_bulk_eamil.php">A/R Bulk Email
                                                        Tool</a>&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                            allows the user to select the templates they want emailed to
                                                            each invoice, and send them all out at once. Program will send
                                                            the emails automatically, 1 every 20 seconds. The table will
                                                            only include UCB invoices (not UCBZW) and will default the
                                                            templates based on how past due they are. Any accounting issues
                                                            or order issues will default as "No Email." Any of these values
                                                            can be changed prior to sending the bulk email.
                                                        </span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="ap_report.php">Payment Request Form Submission
                                                        Tool</a>&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool is
                                                            used to submit requests for payments to suppliers and/or
                                                            vendors. While submitting this form, be sure to Include the
                                                            customer purchasing the items, the payment and/or delivery
                                                            information, and the requested method of shipment.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="ap_report_view.php">Payment Request Summary
                                                        Report</a>&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all of the payment request submissions that have
                                                            been made, and where they are at in the process of being
                                                            paid.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_summary_report_dashboard.php">Supplier
                                                        Dashboard Summary Report</a>&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report shows the user a summary of all
                                                            supplier dashboards, and points out any transactions still
                                                            lingering for the supplier. Having lingering transactions means
                                                            that UCB cannot make payment for the full month of transactions
                                                            just yet, or risk shorting the payment.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_vendor_payment_qb_list.php">Vendor
                                                        Payment and QB entry mismatch Report</a>&nbsp;
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user a summary of all supplier dashboards, and points
                                                            out any transactions still lingering for the supplier. Having
                                                            lingering transactions means that UCB cannot make payment for
                                                            the full month of transactions just yet, or risk shorting the
                                                            payment.</span></div>
                                                </td>
                                            </tr>

                                            <?php
                                            if ($rec_found == "super_yes") {
                                            ?>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_commissions.php">Commission
                                                            Report</a>&nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                summarizes all of the sales transactions where the customer's
                                                                invoice is paid in full. You can search these transactions by
                                                                those which do not have the costs confirmed yet, those that do
                                                                have the costs confirmed and are awaiting payout to the sales
                                                                rep, and those which have been paid out to the sales rep
                                                                previously.</span></div>
                                                    </td>
                                                </tr>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_profit_loss.php">UCB P&L Review
                                                            Report</a>&nbsp;
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                            <span class="tooltiptext">This report allows the user to select a
                                                                date range and view the revenue, cost of goods sold (COGS),
                                                                gross profit (not to be confused with NET profit), and margins.
                                                                The '# of transactions not double checked' number represents all
                                                                of the transactions within the column of data which do not have
                                                                the costs finalized yet, meaning the numbers aren't fully
                                                                accurate yet either.</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_gross_profit_exception.php">Gross Profit
                                                            Exception Report</a>&nbsp;
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                            <span class="tooltiptext">This report allows the user to find
                                                                exceptions to our average profitability in order to identify
                                                                user errors, such as missed costs being entered.</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_profit_loss_amount_history.php">UCB P&L
                                                            report affecting payment List</a>&nbsp;
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                            <span class="tooltiptext">This report shows the Profit and Loss
                                                                report affecting payment List.</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_vendor_payment_qb_details.php">Vendor
                                                        payment QB detail List</a>&nbsp;
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This report shows the Vendor payment QB
                                                            detail List.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_vendor_payment_not_updated_mismatch.php">Vendor Payment
                                                        Not updated/mismatch Report</a>&nbsp;
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This report shows the Vendor Payment Not
                                                            updated/mismatch List.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_mark_as_mismatch_list.php">Vendor
                                                        Payment Cost Mismatch Report</a>&nbsp;
                                                    <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        <span class="tooltiptext">This report shows the Vendor Payment Cost
                                                            Mismatch Report List.</span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                            <!--//////////////////End Finance links/////////////////////////-->
                                            <!--//////////////////B2B Supplier Portals/////////////////////////-->
                                        </table>
                                    </td>
                                    <td style="width: 50px;">&nbsp;</td>
                                    <td valign="top">
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>B2B Supplier Dashboards</strong>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="upload_files_to_dashboard.php">Add Files to ALL
                                                        Supplier Portals Tool</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                            allows the user to upload a file to ALL suppler dashboards in
                                                            the system. If you need to upload a file to just a singular
                                                            supplier dashboard, the use the Add Files to ONE Supplier Portal
                                                            Tool.</span></div>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="upload_files.php">Add Files to ONE Supplier
                                                        Portal Tool</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                            allows the user to upload a file to ONE suppler dashboard in the
                                                            system. If you need to upload a file to all supplier dashboards,
                                                            use the Add Files to ALL Supplier Portals Tool.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="demodashboard.php">B2B Supplier Dashboard -
                                                        Demo</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_4EXCA_49865742152.php">4Excelsior -
                                                        Anaheim, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_AERO_7234FV9222425HP544847844.php">Aerofil -
                                                        Sullivan, MO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_ANDFO_82346567131223414847844.php">Andros Foods -
                                                        Mt. Jackson, VA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_ARICA_86131223414847844.php">ARI
                                                        Packaging - Ontario, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_BVFWI_4322344556764268847567844.php">Bay Valley
                                                        Foods - Green Bay, WI Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_BD_76348234234.php">BD - Hunt Valley,
                                                        MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_BAYTN_58739648512.php">Beiersdorf -
                                                        Cleveland, TN Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_BPEH_573436452367472841884.php">Berry
                                                        Global - Easthampton, MA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_BOBMD_4678945852.php">Bob's DIscount
                                                        Furniture - Aberdeen, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_CAFEAZ_1567945846523541.php">Cafe
                                                        Vallry Bakery - Phoenix, AZ Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_CNGNV_234387284188478311.php">Conagra
                                                        - Sparks, NV Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_COTMD_112358132134998.php">COTY inc.
                                                        (Beaver Ct) - Cockeysville, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_COTMD_4958764258.php">COTY inc.
                                                        (Wight Rd) - Cockeysville, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_DAP_741118256583788784548.php">DAP -
                                                        Baltimore, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_DCINT_24324242442348847844.php">DC
                                                        International - Carollton, TX Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_DRBR_8733445674393343841884.php">Dr.
                                                        Bronners - Vista, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_DSW_751058209964825.php">DS Waters of
                                                        America - Ephrata, PA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_FEDMO_31241228718874514847844.php">Federal
                                                        International - St. Lous, MO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GTPMD_31188744226874514847844.php">Georgetown
                                                        Paperstock - Rockville, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GOJOH_7234F425HP544V9222847844.php">GOJO - Cuyahoga
                                                        Falls, OH Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GOJOH_95864721548963.php">GOJO -
                                                        Wooster, OH Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_HNZSD_87445674393965644372841884.php">Heinz - San
                                                        Diego, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_KELKS_98642587412.php">Kellogg's -
                                                        Kansas City, KS Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_KGMSW_24324242442348847844.php">GMCR
                                                        Keurig - Summner, WA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMCRVA_234643228847844.php">GMCR
                                                        Keurig - Windsor, VA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_KOSON_1649784563152.php">Kosei -
                                                        Alliston, ON Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_KOSOH_16798459875.php">Kosei - Mason,
                                                        OH Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_KCCCA_18872242344514847844.php">Kroger - Compton, CA
                                                        Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_KSAOH_7224324644514847844.php">Kroger
                                                        - Cincinnati, OH Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_LEVCA_72457922435544847844.php">LEVLAD - Chatsworth,
                                                        CA</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_MRTCHGO_443742342348847844.php">Marietta - Chicago,
                                                        IL Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_MRTCRT_443742342348847844.php">Marietta - Courtland,
                                                        IL Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_MRTLA_4527438728418847844.php">Marietta - Los
                                                        Angeles, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_mcSalinas_4957846326.php">McCormick
                                                        DC - Salinas, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_mcBelcamp_743845982.php">McCormick DC
                                                        - Belcamp, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_mcfmc_297423423.php">McCormick FMC -
                                                        Hunt Valley, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_mcFRENCH_467598452152.php">McCormick
                                                        French's - Springfield, MO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="mccormickdashboard.php">McCormick HVP - Hunt
                                                        Valley, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_mcspice_297423423.php">McCormick SM -
                                                        Hunt Valley, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_mcTIC_7985416485.php">McCormick TIC -
                                                        Hunt Valley, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="mccormick_mlcdashboard.php">McCormick MLC -
                                                        Sparrows Point, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_MLMMO_31242318874514847844.php">Material Lifecycle
                                                        Management (MLM) - Bridgeton, MO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_NESGER_39396564438744567472841884.php">Nestle Gerber
                                                        - Freemont, MI Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_NOOCO_2345754643228847844.php">Noosa
                                                        - Bellvue, CO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_PAKLA_92243572457544847844.php">PakLab - Chino, CA
                                                        Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_PEPNPN_4527438728418847844.php">Pepsi
                                                        - Newport News, VA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_PEPPHI_66438728418847844.php">Pepsi -
                                                        Philiadelphia, PA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_RBLMO_576948652323231.php">RB Lysol -
                                                        St. Peters, MO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_REPMO_5849165423.php">Republic
                                                        Services - Hazelwood, MO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_RPMEV_9448642587412.php">RPM
                                                        Environmental - Geneva, IL Dashboard</a>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_RYTWAY_45643359923686129365.php">Ryt-Way -
                                                        Lakeville, MN Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_SCHLA_34257456452348567844.php">Schreiber Foods -
                                                        Fullerton, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_SEAB_2669787244188478311.php">Seabrook Bros & Sons -
                                                        Bridgeton, NJ Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_SESMO_164879798541.php">Simplified
                                                        E-Solutions (SES) - Hazelwood, MO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_SFF_751058223549964825.php">Smith
                                                        Frozen Foods - Weston, OR Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_SNACA_4568521483875.php">Snak King -
                                                        City of Industry, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_SONSC_4975214598632.php">Sonoco
                                                        Crellin - Union, SC Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_SNDTX_34257456452348567844.php">Sunny
                                                        D - Sherman, TX Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_TAYMD_4675841958751.php">Taylor Farms
                                                        - Annapolis Junction, MD Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_FL_705809964825.php">The Finish Line
                                                        - Indianapolis, IN Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_TREMA_7225HP544V922B2847844.php">TreeHouse Foods -
                                                        Manawa, WI Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_UNIMO_64875914751355.php">Unilever -
                                                        Jefferson City, MO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_VENTMO_49576428145678.php">Ventura
                                                        Foods - St. Joseph, MO Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_VERPA_49857218579.php">Verizon -
                                                        Arbutis, PA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_VPETIL_43572457544847844.php">VPET -
                                                        Romeoville, IL Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_VPETCA_7245754643444847844.php">VPET
                                                        - Fontana, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_VPETTX_43572457544847844.php">VPET -
                                                        Garland, TX Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_WOFCA_29953457357428847844.php">White
                                                        Oak Frozen Foods - Merced, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_WISMS_23566445673574268847567844.php">Wis-Pak -
                                                        Hattiesburg, MS Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_WISIL_2578534573574268847567844.php">Wis-Pak -
                                                        Quincy, IL Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_YOPLA_7225HP54YOP4V9222847844.php">Yoiplait - Los
                                                        Angeles, CA Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                            <!--//////////////////End B2B Supplier Portals links/////////////////////////-->
                                        </table>
                                        <!--////////////////// B2B Customer Portals links/////////////////////////-->
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">

                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>B2B Customer Dashboards</strong>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://boomerang.usedcardboardboxes.com/">B2B
                                                        Customer Online Portal Login</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            login screen for Customers to login to their UCB online
                                                            dashboard.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_client_dash_log.php">B2B Customer Online
                                                        Portal Login Usage Summary Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all logins into the B2B Customer Online Portals
                                                            within a date range.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_client_dash_summary.php">B2B Customer
                                                        Online Portal Login Accounts Summary Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all logins created for the B2B Online
                                                            Customer Portals.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://supplier.usedcardboardboxes.com/">B2B
                                                        Supplier Online Portal Login</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            login screen for Suppliers to login to their UCB online
                                                            dashboard.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_supplier_dash_summary.php">B2B Supplier
                                                        Online Portal Login Usage Summary Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all logins into the B2B Supplier Online Portals
                                                            within a date range.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_supplier_dash_log.php">B2B Supplier
                                                        Online Portal Login Accounts Summary Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to see all logins created for the B2B Online
                                                            Supplier Portals.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="upload_files_to_dashboard.php">Berry Global -
                                                        Hanover, MD Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool allows the user to upload a file to ONE suppler dashboard in the system. If you need to upload a file to all supplier dashboards, use the Add Files to ALL Supplier Portals Tool.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="upload_files_to_dashboard.php">Walmart -
                                                        Hurricane, UT Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool allows the user to upload a file to ONE suppler dashboard in the system. If you need to upload a file to all supplier dashboards, use the Add Files to ALL Supplier Portals Tool.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                            <!--//////////////////End B2B Customer Portals links/////////////////////////-->
                                        </table>
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>B2B Sales Channels</strong>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://supplier.coupahost.com/sessions/new">COUPA Login
                                                        Screen</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the login screen for the COUPA system, which LKQ
                                                            (customer) uses to order boxes.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://supplier.schn.com">Schnitzer Steel
                                                        iSupplier Login Screen</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the login screen for iSupplier, specifically for
                                                            Schnitzer Steel (customer) where we can lookup purchase orders
                                                            and upload invoices for payment.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://solutions.sciquest.com/apps/Router/SupplierLogin?tmstmp=">Schnitzer
                                                        Steel JAGGAER Login Screen</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the login screen for JAGGAER, specifically for Schnitzer
                                                            Steel (customer), where we can upload product catalogs for them
                                                            to buy from.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://www.enabletrading.com/scripts/tg_prod.wsp/logon.htm">Best
                                                        Buy Easy Link Login Screen</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the login screen for the Best Buy Easy Link Login
                                                            Screen, specifically for Best Buy.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://payee.globalpay.westernunion.com/PayeeManager/Beneficiary/Home.aspx">Best
                                                        Buy GlobalPay Payee Manager</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the login screen for the Best Buy GlobalPay Payee
                                                            Manager, specifically for Best Buy, where we view payment data
                                                            related to Best Buy's orders.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://isupplier.bestbuy.com/">Best Buy
                                                        iSupplier Login Screen</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the login screen for iSupplier, specifically for Best
                                                            Buy (customer) where we can lookup purchase orders and upload
                                                            invoices for payment.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>

                                            <!--//////////////////End B2B Customer Portals links/////////////////////////-->
                                        </table>
                                        <!--//////////////////B2C links/////////////////////////-->
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>B2C</strong>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="customer_buy_report.php">Heat Map: Where B2C
                                                        Customer Buy</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This heat map
                                                            shows the user where all B2C customers are located, over the
                                                            lifetime of UCB.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://b2c.usedcardboardboxes.com/login.php">B2C Homepage</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            homepage of the B2C system, which is the system which houses all
                                                            B2C transactions (orders).</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://b2c.usedcardboardboxes.com/report_b2c_emails.php">B2C
                                                        Email Extractor Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            allows the user to generate a list of all customer email first
                                                            names and email addresses which were placed within a date range.
                                                            This can be used to develop email lists.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="order_source_report.php">Order Source
                                                        Report</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This heat map shows the user where all B2C customers are located, over the lifetime of UCB.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="report_box_bucks_code.php">B2C Box Bucks Code
                                                        Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                            shows the user all B2C orders which were placed with a box bucks
                                                            code within a date range.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://app.rangeme.com/login?redirect=%2Fsuppliers%2Fusedcardboardboxes-ucbzerowaste-780ede%2Fconversations%2F314412">Range.me
                                                        Login Screen</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This heat map shows the user where all B2C customers are located, over the lifetime of UCB.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://retaillink.wal-mart.com/rl_portal/#/">Retail Link
                                                        Login Screen</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This heat map shows the user where all B2C customers are located, over the lifetime of UCB.</span></div>-->
                                                </td>
                                            </tr>


                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                            <!--//////////////////End B2C  links/////////////////////////-->
                                        </table>
                                        <!--////////////////// GMI links/////////////////////////-->
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>General Mills International (GMI)</strong>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="https://gmlink.genmills.com/irj/portal">General
                                                        Mills (GMI) SAP Login Screen</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                            link to the SAP system UCB uses with General Mills.</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="general_mills_dashboard.php">General Mills HQ -
                                                        Corporate Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMINM_23457357428847844.php">General
                                                        Mills - Albuquerque, NM Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMIBEL_7234945643241311883845.php">General Mills -
                                                        Belvidere, IL Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMICOH_794465423432424131983.php">General Mills -
                                                        Cinncinnati, OH Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMICOV_435945643248386865.php">General Mills -
                                                        Covinton West, GA Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMIHMO_794465483241311883845.php">General Mills -
                                                        Hannibal, MO Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMIKENT_814594564323686129365.php">General Mills -
                                                        Kentwood, MI</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GML_79832413118333583845.php">General
                                                        Mills - Lodi, CA Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMIMW_24567437942414566484234.php">General Mills -
                                                        Milwaukee, WI Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMIMF_7225HP54YOP4V9222847844.php">General Mills -
                                                        Murfreesboro, TN Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMIVNJ_27437942413138423444.php">General Mills -
                                                        Vineland, NJ Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="dashboard_GMIWO_24567437942414566484234.php">General Mills -
                                                        Wellston, OH Dashboard</a>
                                                    &nbsp;
                                                    <!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the link to the SAP system UCB uses with General Mills.</span></div>-->
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                            <!--////////////////// End  GMI links/////////////////////////-->
                                        </table>

                                        <!--////////////////// UCBZW links/////////////////////////-->
                                        <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                            <tr align="middle">
                                                <td class="style24" style="height: 16px">
                                                    <strong>UCBZeroWaste</strong>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="recyclers_report.php">Loops Records Recyclers
                                                        Report</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="water_pickups_not_deliver.php">Pick-up Requests
                                                        Tracker per Company and Status</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="water_initiatives_report.php">Water Initiatives
                                                        Report</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="UCBZeroWaste_Vendors_AP_AR.php">UCBZeroWaste
                                                        Vendors A/P and A/R</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="water_ucbzerowaste_pipeline_report.php">UCBZeroWaste Pipeline
                                                        per Employee</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="water_invoice_tracker.php">UCBZeroWaste Invoice
                                                        Tracker</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="water_cron_fordash-selectedrecord.php">Water
                                                        Cron Job for Selected Location and Year - Single Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="water_cron_fordash-parent-selectedrecord.php">Water Cron Job
                                                        for Selected Location and Year - Parent Dashboard</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="water_monthly_consolidated_invoices_log.php">UCBZeroWaste
                                                        Monthly Consolidated Invoices Accounting Logs</a>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="water_transaction_ar_reports.php">UCBZeroWaste
                                                        Transactions A/R Report</a>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="client_monthly_side_to_side_reports.php">Contracted Clients
                                                        Monthly Reports Side to Side</a>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="client_monthly_side_to_side_landfill_diversion_reports.php">Contracted
                                                        Clients Monthly Reports Side to Side with Landfill Diversion
                                                        Details</a>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a target="_blank" href="client_monthly_side_to_side_ton_price_analysis_reports.php">Contracted
                                                        Clients Monthly Reports Side to Side with Price per Ton Analysis</a>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="water_index_load_tables_pipeline_new.php?fromdash=yes&tablenm=salespipeline&sort_order_pre=ASC&statusid=83&sort=&emp_list_selected=" target="_blank">UCBZeroWaste Contracted Clients List</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCB Zero Waste
                                                            Table</span></div>
                                                </td>
                                            </tr>
                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="water_index_sales_pipeline.php" target="_blank">UCBZeroWaste
                                                        Sales Pipeline</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCB Zero Waste
                                                            Table Pipeline</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="water_index_clients_water_report_internal.php" target="_blank">WATER Dashboards Login Credentials List</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCB Zero Waste
                                                            Table Pipeline</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="water_index_contracted_clients.php" target="_blank">Contracted
                                                        Clients Implementation Process (Order to Cash Cycle)</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCB Zero Waste
                                                            Contracted Clients</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="water_index_pickup_request_mgmt.php" target="_blank">Pick-up
                                                        Requests Management</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCB Zero Waste
                                                            Pickup Request</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="water_vendor_master_new.php?posting=yes" target="_blank">Vendors Master Database</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCB Zero Waste
                                                            Master Database</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="water_material_master_table.php" target="_blank">WATER
                                                        Materials Master Database</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCB Zero Waste
                                                            Material Database</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="UCBZeroWaste_All_Invoice_list.php" target="_blank">Invoice List
                                                        Report</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCB Zero Waste
                                                            Invoice List Report</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="water_vendor_pallets_supplier.php" target="_blank">UCBZeroWaste
                                                        Contracted Clients Map - Pallet suppliers</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCBZeroWaste
                                                            Contracted Clients Map - Pallet suppliers</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td class="style12">
                                                    <a href="water_vendor_organic_processors.php" target="_blank">UCBZeroWaste Contracted Clients Map - Organic
                                                        Processors</a>
                                                    &nbsp; <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">UCBZeroWaste
                                                            Contracted Clients Map - Organic Processors</span></div>
                                                </td>
                                            </tr>

                                            <tr vAlign="center">
                                                <td bgColor="#FFFFFF" class="style12">&nbsp;
                                                </td>
                                            </tr>
                                        </table>

                                        <?php
                                        if ($rec_found == "super_yes") {
                                        ?>
                                            <table cellSpacing="1" cellPadding="5" border="0" width="500" class="newlinks">
                                                <tr align="middle">
                                                    <td class="style24" style="height: 16px">
                                                        <strong>Outdated/Old</strong>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_daily_chart_mgmt_v2_org.php">
                                                            OLD UCB Leaderboard Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows OLD UCB Leaderboard Report</span></div>
                                                    </td>
                                                </tr>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_daily_chart_mgmt_gprofit_v2_org.php">
                                                            OLD B2B Leaderboard Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows OLD B2B Leaderboard Report.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_sales_team_list_old.php">
                                                            OLD Call List Report - B2B Sales</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user all B2B sales accounts in the system which meet
                                                                the conditions listed. These conditions are designed to generate
                                                                a focused report of accounts which should have a higher
                                                                probability to yield sales. This list is refreshed every night,
                                                                and then divided up evenly and fairly amongst all sales reps on
                                                                the list.</span></div>
                                                    </td>
                                                </tr>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_purchasing_team_list_old.php">
                                                            OLD Call List Report - B2B Purchasing</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user all B2B purchasing accounts in the system which
                                                                meet the conditions listed. These conditions are designed to
                                                                generate a focused report of accounts which should have a higher
                                                                probability to yield sales. This list is refreshed every night,
                                                                and then divided up evenly and fairly amongst all sales reps on
                                                                the list.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_daily_chart_mgmt_purchasing_ver2_org.php">
                                                            OLD B2B Purchasing Leaderboard</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user UCB sales data for all departments, itemized by
                                                                employee and time frame, including a date range selector a the
                                                                top. This report also has child reports to be able to see all
                                                                transactions and what step in the process they are, the number
                                                                of new deals closed by each rep, and an account ownership
                                                                breakdown.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_daily_chart_test_mgmt.php">Sales Review
                                                            Report (Old)</a>
                                                    </td>
                                                </tr>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="production_enter.php">TEST TECH: Entering
                                                            Production From Forklift Tool</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool allow
                                                                the user to enter in production directly from the forklift with
                                                                an ipad as they are putting inventory away. This was a test by
                                                                DK/ZF but without anyone to test it, the tool was coded but
                                                                never used.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="production_sort_report.php">TEST TECH: Entering
                                                            Sort Reports From Forklift Tool</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool allow
                                                                the user to enter in sort reports directly from the forklift
                                                                with an ipad after all inventory has been put away. This was a
                                                                test by DK/ZF but without anyone to test it, the tool was coded
                                                                but never used.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="deals_tracker.php">Purchase Orders Uploaded
                                                            Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This tool
                                                                allows the user to see all purchased orders (signed quotes)
                                                                uploaded within a date range.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="linksZF.php">Zac's Old Links List</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">Zac's old links
                                                                list. This report will be deleted once it is cross checked with
                                                                the full links list on dashboardnew.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="production_enter.php">Old Executive Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user all of the high level information about
                                                                UCB.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_commissions_purchase.php">B2B Purchasing
                                                            Commission Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report is
                                                                used to pay the purchasing reps their commissions.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_find_companies_abletobuy.php">Gaylord
                                                            Matching Tool</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is an
                                                                outdated/old tool, which is no longer used. The newer version is
                                                                the Demand Matching Tool.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="shipping_box_matching_tool.php">Shipping Box
                                                            Matching Tool</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                allows the user to enter details and see matches based on
                                                                shipping box size and deviation.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="production_enter.php">Daily B2B Sales Ticker
                                                            Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user all of the deals which were closed today and
                                                                yesterday, as well as each change to the loops database system.
                                                                These updates include purchases of boxes, sort reports entered,
                                                                shipped orders, etc.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_daily_chart_test_mgmt.php">B2B Sales
                                                            Leaderboard Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user UCB sales data for specifically the B2B
                                                                department, itemized by employee and time frame, including a
                                                                date range selector a the top. This report also has child
                                                                reports to be able to see all transactions and what step in the
                                                                process they are, the number of new deals closed by each rep,
                                                                and an account ownership breakdown.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="order_fulfillment_sales_request.php">Demand
                                                            Entry Summary Tool (Old)</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                allows the user to view a summary of the old demand entry
                                                                system, and mark when sales were completed against the demand
                                                                entry or denying them.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="production_enter.php">Inventory Availability
                                                            Report (Customer Facing)</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                allows the user to view all available inventory, but in a format
                                                                that is safe for customers to be able to view themsevles. It is
                                                                allowed to copy/paste data from this report into emails to allow
                                                                cusotmers to understand what we have available.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="addVendor.php">Vendor Database (Old)</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This old
                                                                database was used to attach companies to inventory items. Now
                                                                that we use the B2B database purchasing records to do this, this
                                                                vendor database is now obsolete.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_inventory_types.php">UCB Company List
                                                            Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">ZF is unsure
                                                                what this report does.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="/dashboardnew.php?show=inventory">Original
                                                            Inventory Summary Report</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the
                                                                original B2B Inventory Report.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="dashboardnew.php?show=inventory_filter">Inventory Report (for
                                                            Sales Reps)</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the old
                                                                test B2B Inventory Report, made specifically for how sales reps
                                                                need to see the inventory.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_inventory_types.php">Inventory Report
                                                            (by types)</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the old
                                                                test B2B Inventory Report, tested and not liked</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="dashboard_genmillscorp_1SDF415HRS786ER79PP92653.php">General
                                                            Mills Corporate Dashboard (Old)</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">TThis is the
                                                                old General Mills dashboard.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="https://business.usedcardboardboxes.com/b2c/">Old UCB Website
                                                            (OS Commerce)</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user all of the high level information about
                                                                UCB.</span></div>
                                                    </td>
                                                </tr>

                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="canusahershmanwarehouse_1614835009.php">UCB-CH
                                                            Warehouse Dashboard (Dundalk, MD)</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This is the old
                                                                Canusa Hirschman Recycling sorting warehouse dashboards
                                                                (UCB-CH).</span></div>
                                                    </td>
                                                </tr>
                                                <tr vAlign="center">
                                                    <td class="style12">
                                                        <a target="_blank" href="report_b2b_demand_summary_old.php">
                                                            B2B Demand Summary Report old</a>
                                                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">This report
                                                                shows the user all demand entries that UCB has the opportunity
                                                                sell to, regardless of whether we satiate that demand or
                                                                not...and sections it by the most valuable demand entries to the
                                                                least. Thus, this report helps the user see the most valuable
                                                                demand entries UCB has in it's entire demand pipeline.</span>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </table>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        <?php
                        }

                        useful_links_new2021();
                        ?>



                    </td>
                </tr>
            </tbody>
        </table>


    </div>

</body>

</html>