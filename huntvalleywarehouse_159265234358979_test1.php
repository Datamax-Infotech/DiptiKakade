<?php
/*require ("inc/database.php");
require ("inc/databaseb2b.php"); 
require ("inc/functions.php");*/
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
require("inc/functions_mysqli.php");
?>

<?php
$warehouse_id_list_str  = "15, 79, 32, 185, 111, 738 ,899 ,1191 ,1027 ,747 ,1514 ,1806 ,1472 ,1473 ,1527 ,1503 ,1972, 1491, 2134 ,2343, 2449, 2636, 2609";
$warehouse_id_list_str2 = "15 ,79 ,32 ,185 ,111 ,738 ,899 ,1191 ,1027 ,747 ,1514 ,1806, 1472 ,1473 ,1527 ,1503 ,1972, 1491, 2134, 2343, 2449, 2636, 2609";
$warehouse_id_list_str3 = "15 ,79 ,32 ,185 ,111 ,738 ,899 ,1191 ,1027 ,747 ,1514 ,1806, 1472 ,1473 ,1527, 1503, 1972, 1491, 2134, 2343, 2449, 2636, 2609";

$location_address = "Hunt Valley Production";
/*
$warehouse_id_list_str  = "15, 79, 32, 185, 111, 738 ,899 ,1191 ,1027 ,747 ,1514 ,1806 ,1472 ,1473 ,1527 ,1503 ,1491 ,1972 ,2134 ,2343, 2449, 2609, 2636"; 
$warehouse_id_list_str2 = "15 ,79 ,32 ,185 ,111 ,738 ,899 ,1191 ,1027 ,747 ,1514 ,1472 ,1473 ,1527 ,1503 ,1972, 2134, 2636, 2449";
$warehouse_id_list_str3 = "15 ,79 ,32 ,185 ,111 ,738 ,899 ,1191 ,1027 ,747 ,1514 ,1472 ,1806, 1972, 1473, 2636";
*/

function huntvalleywarehousepage_test(): string
{
    return "huntvalleywarehouse_14159265358979_test1.php";
}

$urlRefresh = huntvalleywarehousepage_test();
//header("Refresh: 10; URL=".huntvalleywarehousepage_test()); // redirect in 5 seconds
require("inc/header_session.php");

$get_all_red_row_cnt = 0;
$HV_red_row_loop_ids_str = "";

?>


<!DOCTYPE HTML>

<html>
<title>Hunt Valley - Dashboard</title>

<head>
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
            left: 45%;
            background: #ffffff;
        }

        span.infotxt span {
            position: absolute;
            left: -9999px;
            margin: 0px 0 0 0px;
            padding: 3px 3px 3px 3px;
            border-style: solid;
            border-color: black;
            border-width: 1px;
        }

        span.infotxt:hover span {
            margin: 18px 0 0 170px;
            background: #ffffff;
            z-index: 6;
        }

        span.infotxt_freight:hover {
            text-decoration: none;
            background: #ffffff;
            z-index: 6;
        }

        span.infotxt_freight span {
            position: absolute;
            left: -9999px;
            margin: 20px 0 0 0px;
            padding: 3px 3px 3px 3px;
            z-index: 6;
        }

        span.infotxt_freight:hover span {
            left: 0%;
            background: #ffffff;
        }

        span.infotxt_freight span {
            position: absolute;
            left: -9999px;
            margin: 0px 0 0 0px;
            padding: 3px 3px 3px 3px;
            border-style: solid;
            border-color: black;
            border-width: 1px;
        }

        span.infotxt_freight:hover span {
            margin: 18px 0 0 170px;
            background: #ffffff;
            z-index: 6;
        }


        span.so_infotxt:hover {
            text-decoration: none;
            background: #ffffff;
            z-index: 6;
        }

        span.so_infotxt span {
            position: absolute;
            left: -9999px;
            margin: 20px 0 0 0px;
            padding: 3px 3px 3px 3px;
            z-index: 6;
        }

        span.so_infotxt:hover span {
            left: 0%;
            background: #ffffff;
        }

        span.so_infotxt span {
            position: absolute;
            left: -9999px;
            margin: 0px 0 0 0px;
            padding: 3px 3px 3px 3px;
            border-style: solid;
            border-color: black;
            border-width: 1px;
        }

        span.so_infotxt:hover span {
            margin: 18px 0 0 50px;
            background: #ffffff;
            z-index: 6;
        }

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
            padding: 5px;
            border: 2px solid black;
            background-color: white;
            z-index: 1002;
            overflow: auto;
        }
    </style>

    <script LANGUAGE="JavaScript">
        function chkssn() {
            if (document.getElementById('ssn_txt').value.trim() == "") {
                alert("Please enter the last four digit of your SSN.");
                return false;
            } else {
                return true;
            }
        }

        function chkssn_logout(id) {
            if (document.getElementById('ssn_txt_logout' + id).value.trim() == "") {
                alert("Please enter the last four digit of your SSN.");
                return false;
            } else {
                return true;
            }
        }

        function eod_popup(warehousetbl) {
            document.getElementById("hd_warehouse").value = warehousetbl;

            document.getElementById("light").innerHTML =
                "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>" +
                document.getElementById("diveod").innerHTML;
            document.getElementById('light').style.display = 'block';

            document.getElementById('light').style.left = '400px';
            document.getElementById('light').style.top = 100 + 'px';
        }

        function display_file(filename, formtype) {
            document.getElementById("light").innerHTML =
                "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" +
                formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
            document.getElementById('light').style.display = 'block';
            document.getElementById('fade').style.display = 'block';

            document.getElementById('light').style.left = '200px';
            document.getElementById('light').style.top = 50 + 'px';

        }

        function confirmationRequest(a, b, c) {
            var answer = confirm("Request Pickup of Trailer #" + a + "?")
            if (answer) {
                window.location = "<?php echo huntvalleywarehousepage_test() ?>?action=request&req_id=" + b +
                    "&trailer_no=" + a + "&dock=" + c;
            } else {
                alert("Request Cancelled");
            }
        }

        function fn_delivered_to_dock(trailer, recid, editflg, wh_name) {
            selectobject = document.getElementById("dockdoor_" + recid);

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');
                    document.getElementById("light_dd").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_dd').style.display = 'block';
                    //document.getElementById('fade').style.display='block';

                    document.getElementById('light_dd').style.left = (n_left - 300) + 'px'; //(n_left - 300) + 'px';
                    document.getElementById('light_dd').style.height = 370 + 'px';
                    document.getElementById('light_dd').style.width = 600 + 'px';
                    document.getElementById('light_dd').style.top = n_top + 20 + 'px';
                }
            }

            xmlhttp.open("POST", "wa_dock_door_delivered.php?recid=" + recid + "&trailer=" + trailer + "&dd_flg=1" +
                "&editflg=" + editflg + "&wh_name=" + wh_name, true);
            xmlhttp.send();
        }

        function save_unloaded_val(recid) {
            var txtunloadedwhere = document.getElementById("txtunloadedwhere").value;
            var warehouse_id = document.getElementById("warehouse_id").value;
            var wa_dd_save = document.getElementById("wa_dd_save").value;

            var rec_id = document.getElementById("rec_id").value;
            var dock = document.getElementById("dock").value;
            var trailer = document.getElementById("trailer").value;

            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("DockDoor added successfully");
                    document.getElementById('light_dd').style.display = 'none';
                    window.location = "<?php echo huntvalleywarehousepage_test() ?>";
                }
            }

            xmlhttp.open("POST", "wa_unloaded_popup.php?updatedockdoor=1&wa_dd_save=" + wa_dd_save + "&txtunloadedwhere=" +
                txtunloadedwhere + "&warehouse_id=" + warehouse_id + "&rec_id=" + rec_id + "&dock=" + dock +
                "&trailer=" + trailer, true);
            xmlhttp.send();

        }

        function save_dockdoor_val(recid) {
            var srt_dock_doors = document.getElementById("srt_dock_doors").value;
            var warehouse_id = document.getElementById("warehouse_id").value;
            var wa_dd_save = document.getElementById("wa_dd_save").value;
            var wh_name = document.getElementById("wh_name").value;
            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("DockDoor added successfully");
                    document.getElementById('light_dd').style.display = 'none';
                    //document.getElementById('fade').style.display='none';
                    window.location = "<?php echo huntvalleywarehousepage_test() ?>";
                    //$('#indockdoor').load(window.location.href + '#indockdoor');
                }
            }
            xmlhttp.open("POST", "wa_dock_door_delivered.php?updatedockdoor=1&wa_dd_save=" + wa_dd_save +
                "&srt_dock_doors=" + srt_dock_doors + "&warehouse_id=" + warehouse_id + "&rec_id=" + recid +
                "&wh_name=" + wh_name, true);
            xmlhttp.send();

        }

        function confirmationDelivery(a, b, c) {
            var answer = confirm("Confirm Delivery of Trailer #" + a + " to UCB warehouse?")
            if (answer) {

                window.location = "<?php echo huntvalleywarehousepage_test() ?>?action=confirm&conf_id=" + b +
                    "&trailer_no=" + a + "&dock=" + c;
            } else {
                alert("Request Cancelled");
            }
        }

        function confirmationRecycling(a, b, c) {
            var answer = confirm("Confirm Trailer #" + a + " is recycling?")
            if (answer) {
                window.location = "<?php echo huntvalleywarehousepage_test() ?>?action=recycling&conf_id=" + b +
                    "&trailer_no=" + a + "&dock=" + c;
            } else {
                alert("Cancelled");
            }
        }

        function confirmationUcblot(a, b, c) {
            var answer = confirm("Confirm Trailer #" + a + " is UCB Lot?")
            if (answer) {
                window.location = "<?php echo huntvalleywarehousepage_test() ?>?action=ucblot&conf_id=" + b +
                    "&trailer_no=" + a + "&dock=" + c;
            } else {
                alert("Cancelled");
            }
        }

        //
        function confirmationUnloaded(a, b, c) {
            var answer = confirm("Confirm Trailer #" + a + " is UCB Unloaded?")
            if (answer) {
                window.location = "<?php echo huntvalleywarehousepage_test() ?>?action=confirm&conf_id=" + b +
                    "&trailer_no=" + a + "&dock=" + c;

            } else {
                alert("Cancelled");
            }
        }


        function confirmationUnloaded_new(trailer, recid, dock, warehouse_id) {


            selectobject = document.getElementById("btnDelivered" + recid);

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');
                    document.getElementById("light_dd").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_dd').style.display = 'block';
                    //document.getElementById('fade').style.display='block';

                    document.getElementById('light_dd').style.left = (n_left - 400) + 'px'; //(n_left - 300) + 'px';
                    document.getElementById('light_dd').style.height = 250 + 'px';
                    document.getElementById('light_dd').style.width = 450 + 'px';
                    document.getElementById('light_dd').style.top = n_top + 20 + 'px';
                }
            }

            xmlhttp.open("POST", "wa_unloaded_popup.php?recid=" + recid + "&dock=" + dock + "&warehouse_id=" +
                warehouse_id + "&trailer=" + trailer + "&dd_flg=1", true);
            xmlhttp.send();

        }

        function timedRefresh(timeoutPeriod) {

            setTimeout("location.reload(true);", timeoutPeriod);

        }

        function expand() {
            if (document.getElementById("pending_ship").innerHTML == "") {
                document.getElementById("pending_ship").innerHTML =
                    "<br/>Loading .....<img src='images/wait_animated.gif' />";

                if (document.getElementById("pending_ship").style.display == "none") { //alert("1");
                    document.getElementById("pending_ship").style.display = "block";
                } else { //alert("2");	
                    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    } else { // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }

                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            document.getElementById("pending_ship").innerHTML = xmlhttp.responseText;
                        }
                        //alert(xmlhttp.responseText);
                    }
                }

                //alert("flyer_pdf.php?id=" + id);
                xmlhttp.open("POST", "pending_shipment_huntvalley.php", true);
                xmlhttp.send();
            } else {
                document.getElementById("pending_ship").style.display = "block";
            }
        }

        function collapse() {
            document.getElementById("pending_ship").style.display = "none";
        }

        function expand_inv() {
            if (document.getElementById("inv").innerHTML == "") {
                document.getElementById("inv").innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

                if (document.getElementById("inv").style.display == "none") {
                    document.getElementById("inv").style.display = "block";
                } else {
                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }

                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            document.getElementById("hide_tr").style.display = "none";
                            document.getElementById("inv").innerHTML = xmlhttp.responseText;
                        }
                    }
                }

                xmlhttp.open("POST", "inv_huntvalley.php", true);
                xmlhttp.send();
            } else {
                document.getElementById("hide_tr").style.display = "none";
                document.getElementById("inv").style.display = "block";
            }
        }

        function collapse_inv() {
            document.getElementById("inv").style.display = "none";
            document.getElementById("hide_tr").style.display = "block";

        }

        function expand_b2cinv() {

            if (document.getElementById("b2c_inv").innerHTML == "") {
                document.getElementById("b2c_inv").innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

                if (document.getElementById("b2c_inv").style.display == "none") { //alert("1");
                    document.getElementById("b2c_inv").style.display = "block";
                } else {
                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }

                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            document.getElementById("hide_b2cinv").style.display = "none";
                            document.getElementById("b2c_inv").innerHTML = xmlhttp.responseText;
                        }
                    }
                }
                xmlhttp.open("POST", "report_b2c_inventory_for_dashboard.php?whid=1", true);
                xmlhttp.send();
            } else {
                document.getElementById("hide_b2cinv").style.display = "none";
                document.getElementById("b2c_inv").style.display = "block";
            }
        }

        function collapse_b2cinv() {
            document.getElementById("b2c_inv").style.display = "none";
            document.getElementById("hide_b2cinv").style.display = "block";

        }

        function dynamic_Select(sort) {
            var skillsSelect = document.getElementById('dropdown');
            var selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
            document.getElementById("temp").value = selectedText;
            //alert(document.getElementById("temp").value);
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
                            //alert(xmlhttp.responseText);
                        }
                    }
                }
                //alert(warehouseid);
                //alert(document.getElementById("temp").value);
                //alert(order_type);
                //alert("pre_order_sort.php?warehouseid=" + warehouseid + "&selectedgrpid_inedit="+document.getElementById("temp").value+"&sort_order="+order_type);
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

        function display_preoder_sel(tmpcnt, reccnt, box_id, wid) {
            if (reccnt > 0) {

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
                        document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp
                            .responseText;
                    }
                }

                xmlhttp.open("GET", "inventory_preorder_childtable.php?box_id=" + box_id + "&wid=" + wid, true);
                xmlhttp.send();

            }
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

        /*Special order complete function*/
        function complete_specialorder_row(ctrlnm, rec_id, completed_flg) {
            /*var selectobject = document.getElementById("complete_so"+ctrlnm); 
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top  = f_getPosition(selectobject, 'Top');*/

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    if (xmlhttp.responseText == "completed") {
                        var table_row = document.getElementById("so" + ctrlnm);
                        table_row.setAttribute('class', 'special_order_green');
                        document.getElementById("complete_so_div" + ctrlnm).innerHTML =
                            "Completed<br><input type='button' name='undo_complete_so' id='undo_complete_so" + ctrlnm +
                            "' onclick='complete_specialorder_row(" + ctrlnm + "," + rec_id +
                            ",0); return false;' value='Undo'>";

                        so_process_complete_email(ctrlnm, rec_id, 1);

                        //document.getElementById("complete_so_div"+ctrlnm).style.display='none';
                        //document.getElementById("undo_complete_so_div"+ctrlnm).style.display='block';

                    }
                    if (xmlhttp.responseText == "undo completed") {
                        var table_row = document.getElementById("so" + ctrlnm);
                        table_row.setAttribute('class', 'special_order_normal');
                        document.getElementById("complete_so_div" + ctrlnm).innerHTML =
                            "<input type='button' name='complete_so' id='complete_so" + ctrlnm +
                            "' onclick='complete_specialorder_row(" + ctrlnm + "," + rec_id +
                            ",1); return false;' value='Complete'>";

                    }
                }
            }

            xmlhttp.open("GET", "complete_special_oder.php?wid=" + ctrlnm + "&completed_flg=" + completed_flg + "&rec_id=" +
                rec_id, true);
            xmlhttp.send();
        }

        function so_process_complete_email(ctrlnm, rec_id, flg) {
            var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
            selectobject = document.getElementById("complete_so_div" + ctrlnm);
            var deadlinedate = document.getElementById("deadlinedate" + ctrlnm).value;
            var pprintdata = document.getElementById("pprintdata" + ctrlnm).value;
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            //alert(quote_id+quote_rq_id);
            document.getElementById('light').style.left = 300 + 'px';
            document.getElementById('light').style.top = n_top + 30 + 'px';
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    document.getElementById("light").innerHTML =
                        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                        xmlhttp.responseText;

                    document.getElementById('light').style.display = 'block';

                }
            }
            xmlhttp.open("POST", "so_complete_process_email.php?wid=" + rec_id + "&rec_id=" + ctrlnm + "&deadlinedate=" +
                deadlinedate + "&pprintdata=" + pprintdata + "&complete_email=1", true);
            xmlhttp.send();
        }

        //
        function btnsendclick_so(id) {
            var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

            tmp_element1 = document.getElementById("txtemailto").value;

            tmp_element3 = document.getElementById("txtemailcc").value;

            tmp_element2 = document.getElementById("email_so");

            tmp_element4 = document.getElementById("txtemailsubject").value;

            tmp_element5 = document.getElementById("hidden_reply_eml").value;

            var warehouse_id = document.getElementById("wid").value;
            var rec_id = document.getElementById("rec_id").value;

            var inst = FCKeditorAPI.GetInstance("txtemailbody");
            var emailtext = inst.GetHTML();
            //alert(emailtext);
            tmp_element5.value = emailtext;

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    if (xmlhttp.responseText == "") {
                        alert("Email Sent.");
                    } else {
                        alert(xmlhttp.responseText);
                        //alert("Email Error.");
                    }
                    document.getElementById('light').style.display = 'none';
                }
            }

            xmlhttp.open("GET", "so_complete_email_send.php?txtemailto=" + tmp_element1 + "&unqid=" + id +
                "&warehouse_id=" + warehouse_id + "&rec_id=" + rec_id + "&txtemailcc=" + tmp_element3 +
                "&txtemailsubject=" + encodeURIComponent(tmp_element4) + "&hidden_sendemail=inemailmode&txtemailbody=" +
                encodeURIComponent(emailtext), true);
            xmlhttp.send();
        }
        //
        /*Special order update function*/
        function update_so_note(ctrlnm, rec_id, empid) {
            /*var selectobject = document.getElementById("complete_so"+ctrlnm); 
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top  = f_getPosition(selectobject, 'Top');*/
            var last_note = escape(document.getElementById("last_note_so" + ctrlnm).value);
            var rec_type = document.getElementById("rect_type_so" + ctrlnm).value;


            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("transinfo" + ctrlnm).innerHTML = xmlhttp.responseText;
                    var trans_note = document.getElementById("trans_note").value;
                    var trans_date = document.getElementById("trans_date").value;
                    document.getElementById("last_note_so" + ctrlnm).value = trans_note;
                    document.getElementById("transdate_div" + ctrlnm).innerHTML = trans_date;
                    alert("Note has been updated successfully");
                }
            }
            xmlhttp.open("POST", "update_note_special_oder.php?wid=" + ctrlnm + "&update_flg=1" + "&empid=" + empid +
                "&rec_id=" + rec_id + "&rec_type=" + rec_type + "&last_note=" + last_note, true);
            xmlhttp.send();
        }


        function displayucbinv(colid, sortflg) {

            document.getElementById("div_ucbinv_w").innerHTML =
                "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    //alert(xmlhttp.responseText);
                    document.getElementById("div_ucbinv_w").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "inventory_displayucbinv_warehouse.php?colid=" + colid + "&wid=18" + "&sortflg=" + sortflg,
                true);
            xmlhttp.send();
        }
    </script>


    <style type="text/css">
        .style7 {
            font-size: x-small;
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

        .style12center {
            font-family: Arial, Helvetica, sans-serif;
            font-size: x-small;
            color: #333333;
            text-align: center;
        }

        .style12right {
            font-family: Arial, Helvetica, sans-serif;
            font-size: x-small;
            color: #333333;
            text-align: right;
        }

        .style12left {
            font-family: Arial, Helvetica, sans-serif;
            font-size: x-small;
            color: #333333;
            text-align: left;
        }

        .style13 {
            font-family: Arial, Helvetica, sans-serif;
        }

        .style14 {
            font-size: x-small;
        }

        .style15 {
            font-size: x-small;
        }

        .style16 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: x-small;
            background-color: #99FF99;
        }

        .style17 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: x-small;
            color: #333333;
            background-color: #99FF99;
        }

        select,
        input {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000000;
            font-weight: normal;
        }

        .special_order_normal {
            background-color: #e4e4e4;
        }

        .special_order_red {
            background-color: #FF0004;
        }

        .special_order_green {
            background-color: #51D337;
        }

        .white_content_dd {
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


</head>
<script language="JavaScript">
    function FormCheck() {
        if (document.BOLForm.trailer_no.value == "" |
            document.BOLForm.dock.value == "" |
            document.BOLForm.fullname.value == "") {
            alert("Please Complete All Field.\n Need help? Call 1-888-BOXES-88");
            return false;
        }
    }
</SCRIPT>
<script type="text/javascript">
    function update_cart() {
        var x
        var total = 0
        var order_total
        for (x = 1; x <= 10; x++) {
            item_total = document.getElementById("weight_" + x)
            total = total + item_total.value * 1
        }
        order_total = document.getElementById("order_total")
        document.getElementById("order_total").value = total.toFixed(0)
    }
</script>

<body onload="JavaScript:timedRefresh(1200000);">
    <div id="light_dd" class="white_content_dd"></div>
    <?php

    if (isset($_REQUEST["btnqtyofkitship"])) {
        $rec_found = "no";
        $unqid = 0;
        $query4 = "SELECT * FROM orders_by_warehouse_eod where warehouse_name = '" . $_REQUEST["hd_warehouse"] . "' and eod_date = '" . date("Y-m-d") . "'";
        db();
        $dt_view_res4 = db_query($query4);
        while ($objQuote4 = array_shift($dt_view_res4)) {
            $rec_found = "yes";
            $unqid = $objQuote4["unqid"];
        }

        $count_shipped = 0;
        $count_pending_ship = 0;

        $warehouse_name = "";
        if ($_REQUEST["hd_warehouse"] == "hv") {
            $warehouse_name = "Hunt Valley";
            $sql_pc = "SELECT * FROM `orders_active_ucb_hunt_valley` inner join orders on orders.orders_id = orders_active_ucb_hunt_valley.orders_id WHERE ship_status LIKE 'Y' and DATE_FORMAT(date_purchased, '%Y-%m-%d') = '" . date("Y-m-d") . "'";
            db();
            $result_pc = db_query($sql_pc);
            $count_shipped = tep_db_num_rows($result_pc);

            $sql_pc = "SELECT * FROM `orders_active_ucb_hunt_valley` where ship_status LIKE 'N'";
            db();
            $result_pc = db_query($sql_pc);
            $count_pending_ship = tep_db_num_rows($result_pc);
        }

        $message = "Following are the EOD details:<br><br>";
        $message .= "Warehouse name: " . $warehouse_name . "<br>";
        if ($count_pending_ship > 0) {
            $message .= "Labels to be Printed: <font color=red>" . $count_pending_ship . "(NOT ALL LABELS PRINTED BY EOD) </font><br>";
            echo "<script>";
            echo "alert('NOT ALL LABELS PRINTED BY EOD');";
            echo "</script>";
        } else {
            $message .= "Labels to be Printed: " . $count_pending_ship . "<br>";
        }
        $message .= "No. of Qty of Kits Shipped (as per database): " . $count_shipped . "<br>";
        if ($count_shipped != $_REQUEST["qtyofkitship"]) {
            $message .= "No. of Qty of Kits Shipped (as per EOD entry): <font color=red>" . $_REQUEST["qtyofkitship"] . "(QTY SHIPPED DIFFERENT FROM QTY PRINTED)</font><br>";
            echo "<script>";
            echo "alert('QTY SHIPPED DIFFERENT FROM QTY PRINTED');";
            echo "</script>";
        } else {
            $message .= "No. of Qty of Kits Shipped (as per EOD entry): " . $_REQUEST["qtyofkitship"] . "<br>";
        }
        $message .= "Date: " . date("Y-m-d H:i:s") . " CT<br>";
        $message .= "Initials: " . $_COOKIE["userinitials"] . "<br>";

        $mailheaders = "From: Loops System <admin@usedcardboardboxes.com>\n";
        $mailheaders .= "MIME-Version: 1.0\r\n";
        $mailheaders .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        mail("customerservice@UsedCardboardBoxes.com", "B2C End of Day status - " . $warehouse_name, $message, $mailheaders);

        if ($rec_found == "yes") {
            $sql = "UPDATE orders_by_warehouse_eod SET eod_date = '" . date("Y-m-d") . "', warehouse_name = '" . $warehouse_name . "', no_of_kits_shipped = '" . $_REQUEST["qtyofkitship"] . "', eod_entry_date = '" . date("Y-m-d H:i:s") . "', eod_entry_done_by = '" . $_COOKIE['userinitials'] . "'	where unqid = '" . $unqid . "'";
            db();
            $result = db_query($sql);
        } else {
            $sql = "Insert into orders_by_warehouse_eod (eod_date, warehouse_name, no_of_kits_shipped, eod_entry_date, eod_entry_done_by) values('" . date("Y-m-d") . "', '" . $warehouse_name . "', '" . $_REQUEST["qtyofkitship"] . "', '" . date("Y-m-d H:i:s") . "', '" . $_COOKIE['userinitials'] . "')";
            db();
            $result = db_query($sql);
        }
    }

    // function getnickname($warehouse_name, $wid)
    // {
    //     $nickname = "";
    //     $b2bid = 0;

    //     $sql = "SELECT b2bid FROM loop_warehouse where id = '" . isset($row["wid"]) . "'";
    //     db();
    //     $result_comp = db_query($sql);
    //     while ($row_comp = array_shift($result_comp)) {
    //         $b2bid = $row_comp["b2bid"];
    //     }

    //     if ($b2bid > 0) {
    //         $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = '" . $b2bid . "'";
    //         echo $sql;
    //         db_b2b();
    //         $result_comp = db_query($sql);
    //         while ($row_comp = array_shift($result_comp)) {
    //             if ($row_comp["nickname"] != "") {
    //                 $nickname = $row_comp["nickname"];
    //             } else {
    //                 $tmppos_1 = strpos($row_comp["company"], "-");
    //                 if ($tmppos_1 != false) {
    //                     $nickname = $row_comp["company"];
    //                 } else {
    //                     if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
    //                         $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
    //                     } else {
    //                         $nickname = $row_comp["company"];
    //                     }
    //                 }
    //             }
    //         }
    //     } else {
    //         $nickname = $warehouse_name;
    //     }

    //     return $nickname;
    // }


    if ($_REQUEST["action"] == "request") {

        $str_email = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td><p align=\"center\"><a href=\"http://loops.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"http://loops.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";
        $str_email .= "Dear A & S Trucking,<br><br>This email is to request the pickup of Trailer # " . $_REQUEST["trailer_no"] . " from McCormick & Company. Details below:<br><br>";
        $str_email .= "McCormick Dock #:  <b>" . $_REQUEST["dock"] . "</b> <br>";
        $str_email .= "Trailer #:  <b>" . $_REQUEST["trailer_no"] . "</b> <br><br>";
        $str_email .= "Please deliver to:<br><b>Used Cardboard Boxes<br>350 Clubhouse Rd<br>Suite F-G<br>Hunt Valley, MD 21031</b><br>";
        $str_email .= "If you have any questions, please contact David Krasnow at 310-402-8059<br><br>";
        $str_email .= "Best Regards<br>";
        $str_email .= "UsedCardboardBoxes.com<br>";
        $str_email .= "</font></td></tr><tr><td><p align=\"center\"><img width=\"650\" height=\"87\" src=\"http://loops.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr></table></body></html>";

        $recipient = "huntvalley@usedcardboardboxes.com, davidkrasnow@usedcardboardboxes.com, martymetro@usedcardboardboxes.com";
        $subject = "UsedCardboardBoxes.com Trailer Pickup Request";
        $mailheadersadmin = "From: UsedCardboardBoxes.com <operations@UsedCardboardBoxes.com>\n";
        $mailheadersadmin .= "MIME-Version: 1.0\r\n";
        $mailheadersadmin .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($recipient, $subject, $str_email, $mailheadersadmin);


        $sql3ud = "UPDATE loop_transaction SET pa_vendor = 'A&S Trucking Service, Inc', pa_warehouse = 18, pa_pickupdate = '" . date("m/d/Y") . "', pa_employee = 'UCB-HV', pa_date = '" . date("m/d/Y") . "' WHERE id = '" . $_REQUEST["req_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);


        huntvalleywarehouse();
    }

    if ($_REQUEST["action"] == "confirm") {
        $str_email = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td><p align=\"center\"><a href=\"http://loops.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"http://loops.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";
        $str_email .= "Dear UsedCardboardBoxes.com,<br><br>This email is to confirm delivery of Trailer # " . $_REQUEST["trailer_no"] . " from McCormick & Company. Details below:<br><br>";
        $str_email .= "McCormick Dock #:  <b>" . $_REQUEST["dock"] . "</b> <br>";
        $str_email .= "Trailer #:  <b>" . $_REQUEST["trailer_no"] . "</b> <br><br>";
        $str_email .= "Delivered to:<br><b>Used Cardboard Boxes<br>350 Clubhouse Rd<br>Suite F-G<br>Hunt Valley, MD 21031</b><br>";
        $str_email .= "Best Regards<br>";
        $str_email .= "UsedCardboardBoxes.com<br>";
        $str_email .= "</font></td></tr><tr><td><p align=\"center\"><img width=\"650\" height=\"87\" src=\"http://loops.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr></table></body></html>";

        $recipient = "davidkrasnow@usedcardboardboxes.com, martymetro@usedcardboardboxes.com";
        $subject = "Notification: Trailer Delivered to UCB - Clubhouse Rd";
        $mailheadersadmin = "From: UsedCardboardBoxes.com <operations@UsedCardboardBoxes.com>\n";
        $mailheadersadmin .= "MIME-Version: 1.0\r\n";
        $mailheadersadmin .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($recipient, $subject, $str_email, $mailheadersadmin);

        $sql3ud = "UPDATE loop_transaction SET pa_warehouse = '18', bol_file = 'No BOL', bol_employee = 'UCB-HV', bol_date = '" . date("m/d/Y") . "', pa_pickupdate = '" . date("m/d/Y") . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);

        $sql3ud = "UPDATE loop_transaction SET cp_notes = 'Delivery Confirmed via Warehouse Dashboard', cp_employee = 'UCB-HV', cp_date = '" . date("m/d/Y") . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);
        //
        $ucbunloaded_note = "Entered via warehouse[HV] dashboard";
        $sql3ud = "UPDATE loop_transaction SET ucbunloaded_flg = 1, ucbunloaded_note = '" . $ucbunloaded_note . "', ucbunloaded_by= '" . $_COOKIE['userinitials'] . "', ucbunloaded_dt = '" . date("Y-m-d H:i:s") . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);
        //
        huntvalleywarehouse();
    }

    if ($_REQUEST["action"] == "undodelivery") {

        $sql3ud = "UPDATE loop_transaction SET ucbunloaded_flg = 0, ucbunloaded_note ='', area_unloaded='', ucbunloaded_by ='', ucbunloaded_dt = null, cp_notes= '', cp_employee = '', cp_date ='' WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);
        //
        huntvalleywarehouse();
    }

    if ($_REQUEST["action"] == "undorecycling") {
        //pa_warehouse = '' 
        $sql3ud = "UPDATE loop_transaction SET pr_recycling = 0 WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);

        huntvalleywarehouse();
    }


    if ($_REQUEST["action"] == "recycling") {
        $sql3ud = "UPDATE loop_transaction SET pr_recycling = 1, pa_warehouse = 18 WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);

        huntvalleywarehouse();
    }

    if ($_REQUEST["action"] == "undoucblot") {
        //pa_warehouse = '',
        $sql3ud = "UPDATE loop_transaction SET pr_ucblot = 0, pr_ucblot_note='', pr_ucblot_by='' WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);

        huntvalleywarehouse();
    }

    if ($_REQUEST["action"] == "undoucbdockdoor") {
        //$sql3ud = "UPDATE loop_transaction SET srt_dockdoors_flg = 0, srt_dock_doors = '' , srt_ucbdockdoor_note = '', srt_ucbdockdoor_by = '' WHERE id = '". $_REQUEST["conf_id"] ."'";
        //$result3ud = db_query($sql3ud,db() );
        //

        $sql3ud = "UPDATE loop_transaction SET srt_dockdoors_flg = 0, srt_dock_doors = '' , srt_ucbdockdoor_note = '', srt_ucbdockdoor_by = '', cp_notes = '', bol_employee = '', pr_recycling = 0, pr_ucblot = 0, srt_dockdoors_flg=0 WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);
        //
        huntvalleywarehouse();
    }

    if ($_REQUEST["action"] == "ucblot") {
        //$sql3ud = "UPDATE loop_transaction SET pr_ucblot = 1, pa_warehouse = 18 WHERE id = '". $_REQUEST["conf_id"] ."'";

        $ucblot_dt = date("Y-m-d H:i:s");
        $pr_ucblot_note = "Entered via warehouse [HV] dashboard";
        //
        $sql3ud = "UPDATE loop_transaction SET pr_ucblot = 1, pa_warehouse = 18, pr_ucblot_by='" . $_COOKIE['userinitials'] . "', pr_ucblot_dt='" . $ucblot_dt . "' , pr_ucblot_note='" . $pr_ucblot_note . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);

        huntvalleywarehouse();
    }
    if ($_REQUEST["action"] == "ucbunloaded") {
        $ucbunloaded_note = "Entered from warehouse[HV] dashboard";
        $sql3ud = "UPDATE loop_transaction SET ucbunloaded_flg = 1, ucbunloaded_note = '" . $ucbunloaded_note . "', ucbunloaded_by= '" . $_COOKIE['userinitials'] . "', ucbunloaded_dt = '" . date("Y-m-d H:i:s") . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
        db();
        $result3ud = db_query($sql3ud);

        huntvalleywarehouse();
    }


    $emp_login_msg = "";
    $fraud_found = 0;
    if ($_REQUEST["action"] == "clockin") {
        if ($_REQUEST["worker"] > 0) {
            $rec_bypass = "no";

            $sql_chk = "select user_pwd, user_masterpin from loop_workers where id = '" . $_REQUEST["worker"] . "'";
            db();
            $result_chk = db_query($sql_chk);
            $rec_found = "notfound";
            $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
            while ($row_chk = array_shift($result_chk)) {
                $rec_found = "found";
                if ($row_chk["user_pwd"] != "0") {
                    if ($row_chk["user_pwd"] != $_REQUEST["ssn_txt"]) {
                        $rec_bypass = "err";
                        $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
                    } else {
                        $emp_login_msg = "";
                    }
                }
            }

            if ($rec_bypass == "err") {
                $sql_chk = "select variablevalue from tblvariable where variablename = 'master_pin'";
                db();
                $result_chk = db_query($sql_chk);
                while ($row_chk = array_shift($result_chk)) {
                    if ($row_chk["variablevalue"] != $_REQUEST["ssn_txt"]) {
                        $rec_bypass = "err";
                        $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
                    } else {
                        $emp_login_msg = "";
                        $rec_bypass = "no";
                    }
                }
            }

            if ($rec_bypass == "no") {
                //
                //$sql3ud = "INSERT INTO loop_timeclock (`worker_id` ,`warehouse_id` ,`time_in`, `type`, `ipaddress`) VALUES ('" . $_REQUEST["worker"] . "', '18', NOW() + INTERVAL 1 HOUR, '" . $_REQUEST["type"] . "', '" . $_SERVER["REMOTE_ADDR"] . "')";
                $sql3ud = "INSERT INTO loop_timeclock (`worker_id` ,`warehouse_id` ,`time_in`, `location_address`, `type`, `ipaddress`) VALUES ('" . $_REQUEST["worker"] . "', '18', NOW() + INTERVAL 1 HOUR, '" . $location_address . "', '" . $_REQUEST["type"] . "', '" . $_SERVER["REMOTE_ADDR"] . "')";
                db();
                $result3ud = db_query($sql3ud);
                //echo $sql3ud;

                $ipcheckqery = "select * from timeclock_check_ip where loop_warehouse_id=18 and ipaddress='" . $_SERVER["REMOTE_ADDR"] . "'";
                db();
                $ipcheckqery_r = db_query($ipcheckqery);
                $row_ipcheck = array_shift($ipcheckqery_r);

                if (tep_db_num_rows($ipcheckqery_r) > 0) {
                    //
                    $fraud_found = 0;
                    huntvalleywarehouse();
                } else {
                    $worker_selected = $_REQUEST["worker"];
                    $fraud_found = 1;
                }
            }
        }
    }

    if ($_REQUEST["action"] == "clockout") {
        $rec_bypass = "no";
        $sql_chk = "select user_pwd, user_masterpin from loop_workers where id = '" . $_REQUEST["worker_clockout"] . "'";
        db();
        $result_chk = db_query($sql_chk);
        $rec_found = "notfound";
        $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
        while ($row_chk = array_shift($result_chk)) {
            $rec_found = "found";
            if ($row_chk["user_pwd"] != "0") {
                if ($row_chk["user_pwd"] != $_REQUEST["ssn_txt_logout"]) {
                    $rec_bypass = "err";
                    $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
                } else {
                    $emp_login_msg = "";
                }
            }
        }

        if ($rec_bypass == "err") {
            $sql_chk = "select variablevalue from tblvariable where variablename = 'master_pin'";
            db();
            $result_chk = db_query($sql_chk);
            while ($row_chk = array_shift($result_chk)) {
                if ($row_chk["variablevalue"] != $_REQUEST["ssn_txt_logout"]) {
                    $rec_bypass = "err";
                    $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
                } else {
                    $emp_login_msg = "";
                    $rec_bypass = "no";
                }
            }
        }

        if ($rec_bypass == "no") {
            $sql3ud = "UPDATE loop_timeclock SET time_out = NOW() + INTERVAL 1 HOUR, ipaddress_clkout = '" . $_SERVER["REMOTE_ADDR"] . "' WHERE id = '" . $_REQUEST["id"] . "'";
            db();
            $result3ud = db_query($sql3ud);
            //echo $sql3ud;

            $ipcheckqery = "select * from timeclock_check_ip where loop_warehouse_id=18 and ipaddress='" . $_SERVER["REMOTE_ADDR"] . "'";
            db();
            $ipcheckqery_r = db_query($ipcheckqery);
            $row_ipcheck = array_shift($ipcheckqery_r);

            if (tep_db_num_rows($ipcheckqery_r) > 0) {
                $fraud_found = 0;
                huntvalleywarehouse();
            } else {
                $worker_selected = $_REQUEST["worker_clockout"];
                $fraud_found = 1;
            }
        }
    }
    //
    if ($fraud_found == 1) {
        $query = "SELECT loop_workers.name, loop_workers.id, loop_warehouse.id AS whid, loop_warehouse.company_name AS wh_name FROM loop_workers INNER JOIN loop_warehouse ON loop_workers.warehouse_id = loop_warehouse.id WHERE loop_workers.id= '" . isset($worker_selected) . "'";
        db();
        $res = db_query($query);
        $w_row = array_shift($res);
        $worker_name = $w_row["name"];
        $worker_loc = $w_row["wh_name"];
        //
        //send email
        $fraud_email = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td><p align=\"center\"><a href=\"http://loops.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"http://loops.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";
        if ($_REQUEST["action"]    == "clockout") {
            $fraud_email .= "Dear David,<br><br>Employee <b>logout</b> from different IP. Below are details,<br><br>";
        } else {
            $fraud_email .= "Dear David,<br><br>Employee <b>login</b> from different IP. Below are details,<br><br>";
        }
        $fraud_email .= "Name: <b>" . $worker_name . "</b><br>";
        $fraud_email .= "IP Address:  <b>" . $_SERVER["REMOTE_ADDR"] . "</b> <br><br>";
        $fraud_email .= "Location:  <b>UCB - HV</b> <br><br>";

        //$str_email.= "If you have any questions, please contact David Krasnow at 310-402-8059<br><br>";
        $fraud_email .= "Best Regards<br>";
        $fraud_email .= "UsedCardboardBoxes.com<br>";
        $fraud_email .= "</font></td></tr><tr><td><p align=\"center\"><img width=\"650\" height=\"87\" src=\"http://loops.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr></table></body></html>";

        $f_recipient = "davidkrasnow@usedcardboardboxes.com";
        //$f_recipient = "prasad.brid@gmail.com";
        $f_subject = "Time-Clock fraud warning";
        $mailheadersadmin1 = "From: UsedCardboardBoxes.com <operations@UsedCardboardBoxes.com>\n";
        $mailheadersadmin1 .= "MIME-Version: 1.0\r\n";
        $mailheadersadmin1 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        // mail($f_recipient,$f_subject,$fraud_email,$mailheadersadmin1);
        //echo $fraud_email;

        huntvalleywarehouse();
    }
    //

    echo "<script type=\"text/javascript\">";
    echo "function display_preoder() {";
    echo " var totcnt = document.getElementById('inventory_preord_totctl').value;";

    echo " for (var tmpcnt = 1; tmpcnt < totcnt; tmpcnt++) {";
    echo " if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') ";
    echo " { document.getElementById('inventory_preord_top_' + tmpcnt).style.display='none'; } else {";
    echo "  document.getElementById('inventory_preord_top_' + tmpcnt).style.display='table-row'; } ";

    echo "}";
    echo "}";


    echo "</script>";
    ?>


    <!---- TABLE TO FORMAT ----------->
    <table border="0">
        <tr>
            <td width="30%">
                <img src="ucb_logo.jpg">
            </td>
            <td align=center width="50%">
                <font face="Ariel" size="5">
                    <b>UsedCardboardBoxes.com<br></b>
                    Dashboard Report for:<br>
                    <b><i>UCB - Hunt Valley</i></b>
                    </i>

            </td>
            <td align="right" width="20%">
                <img src="new_interface_help.gif">
            </td>
        </tr>
        <tr>
            <td valign="top">

            </td>
            <td width="100">&nbsp;

            </td>
            <td valign="top">

            </td>
        </tr>
    </table>

    <table border="0">
        <tr>
            <td valign="top">
                <!--------------- EMPLOYEE TABLE ---------------->
                <form method="post" action="<?php echo huntvalleywarehousepage_test() ?>" onsubmit="return chkssn();">
                    <input type=hidden name="action" value="clockin">
                    <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

                        <tr align="middle">
                            <td class="style7" colspan="5">
                                <b>TIMECLOCK - WHO IS WORKING?</b>
                            </td>
                        </tr>
                        <tr align="middle">
                            <td colspan="5">
                                <?php echo $emp_login_msg; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="style17" align="center">
                                <b>NAME</b>
                            </td>
                            <td class="style17" align="center">
                                <b>TIME IN</b>
                            </td>
                            <td class="style17" align="center">
                                <b>TYPE</b>
                            </td>
                            <td class="style17" align="center">
                                <b>IP</b>
                            </td>
                            <td class="style5" align="center">
                                <b>LOGOUT</b>
                            </td>

                        </tr>

                        <tr vAlign="center">
                            <td bgColor="#e4e4e4" class="style3" align="center">
                                <select id="worker" name="worker">
                                    <option value="-1">Select Worker</option>
                                    <?php
                                    //$query = "SELECT * FROM loop_workers WHERE warehouse_id = 18 AND active = 1 ORDER BY name ASC";
                                    $query = " SELECT * FROM loop_workers WHERE warehouse_id = 18 and active = 1 and location_address='" . $location_address . "' and ";
                                    //$query = " SELECT * FROM loop_workers WHERE warehouse_id = 18 and active = 1 and ";
                                    $query .= " id not in (select worker_id from loop_timeclock where warehouse_id = 18 and time_out = '0000-00-00 00:00:00' AND loop_timeclock.id > 66800 group by worker_id) ORDER BY name ASC";
                                    db();
                                    $res = db_query($query);
                                    while ($row = array_shift($res)) {

                                    ?>
                                        <option value="<?php echo $row["id"] ?>"><?php echo $row["name"] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td bgColor="#e4e4e4" class="style3" align="center">
                                <select id="type" name="type">
                                    <option value="Production">Production</option>
                                    <!-- <option value="kits">Kits</option> -->
                                    <option value="Forklift">Lead/Forklift</option>
                                    <option value="Office">Office</option>
                                    <!-- <option value="Machines">Machines</option> -->
                                    <!-- <option value="Baler">Baler</option> -->
                                </select>
                            </td>
                            <?php
                            //   $date1 = mktime(date("H") + 3, date("i"), date("s"), date("m"), date("d"), date("Y"));
                            $date1 = mktime(
                                (int) date("H") + 3,
                                (int) date("i"),
                                (int) date("s"),
                                (int) date("m"),
                                (int) date("d"),
                                (int) date("Y")
                            );

                            ?>
                            <td bgColor="#e4e4e4" class="style3" align="center" colspan="3">
                                SSN (last 4 digit) <input type="password" name="ssn_txt" id="ssn_txt" value="" size="4" />
                                <input style="cursor:pointer;" type=submit value="CLOCK IN">
                            </td>
                        </tr>
                </form>

                <?php
                //$query = "SELECT loop_timeclock.id AS A, loop_workers.name AS B, loop_timeclock.time_in AS C, loop_timeclock.type AS D, loop_timeclock.ipaddress AS IP, loop_timeclock.worker_id  FROM loop_timeclock INNER JOIN loop_workers ON loop_timeclock.worker_id = loop_workers.id WHERE loop_timeclock.time_out = '0000-00-00 00:00:00' AND loop_timeclock.warehouse_id = 18 and loop_timeclock.location_address='".$location_address."' ORDER BY loop_timeclock.time_in ASC";
                $query = "SELECT loop_timeclock.id AS A, loop_workers.name AS B, loop_timeclock.time_in AS C, loop_timeclock.type AS D, loop_timeclock.ipaddress AS IP, loop_timeclock.worker_id  FROM loop_timeclock INNER JOIN loop_workers ON loop_timeclock.worker_id = loop_workers.id WHERE loop_timeclock.time_out = '0000-00-00 00:00:00' AND loop_timeclock.warehouse_id = 18 ORDER BY loop_timeclock.time_in ASC";
                db();
                $res = db_query($query);
                while ($row = array_shift($res)) {
                ?>

                    <form method="post" action="<?php echo huntvalleywarehousepage_test() ?>" onsubmit="return chkssn_logout(<?php echo $row["A"] ?>);">
                        <input type=hidden name="action" value="clockout">
                        <input type=hidden name="id" value="<?php echo $row["A"] ?>">
                        <input type=hidden name="worker_clockout" value="<?php echo $row["worker_id"] ?>">

        <tr vAlign="center">
            <td bgColor="#e4e4e4" class="style3" align="center" width="200px;">
                <?php echo $row["B"]; ?></td>
            <td bgColor="#e4e4e4" class="style3" align="center" width="150px;">
                <?php echo date('h:i:s A m/d/Y', strtotime($row["C"])); ?>
            </td>
            <td bgColor="#e4e4e4" class="style3" align="center" width="50px;">
                <?php echo $row["D"]; ?>
            </td>
            <td bgColor="#e4e4e4" class="style3" align="center" width="50px;">
                <a href="http://whatismyipaddress.com/ip/<?php echo $row["IP"]; ?>" target="_blank">
                    <? echo $row["IP"]; ?>
            </td>
            <td bgColor="#e4e4e4" align=middle class="style3" width="250px;">
                SSN (last 4 digit) <input type="password" name="ssn_txt_logout" id="ssn_txt_logout<?php echo $row["A"] ?>" value="" size="4" />
                <input style="cursor:pointer;" type=submit value="Clock Out">
            </td>
        </tr>
        </form>

    <?php
                }
    ?>
    </table>
    </td>
    <!--------------- END EMPLOYEE TABLE ---------------->
    <td valign="top">

        <!--------------- PRODUCTION TABLE ---------------->
        <table cellSpacing="1" cellPadding="1" border="0" width="500px;">
            <?php
            /*if (date("d") < 16)
{ $dt = date('Y-m-01');
	$dttitle = date('m/01/Y');

 }
else { $dt = date('Y-m-16'); 
	$dttitle = date('m/16/Y');

}*/

            //To find out the bi weekly start date
            $dt = date('Y-m-01');
            $dttitle = date('m/01/Y');

            //$date = new DateTime(date('Y-m-d', strtotime('first monday of January' . date('Y'))));
            //$date = new DateTime(date('Y-01-01'));

            $date = new DateTime(date('Y') - 1 . '-12-29');
            //$date = new DateTime(date("Y-m-d" ,strtotime('last tuesday of last month')));
            $curr_date = date('m/d/Y');
            $interval = new DateInterval("P2W");

            for ($i = 1; $i <= 52; $i++) {
                //$week_firstdt = date('m/d/Y', strtotime($date->format("m/d/Y"). ' + 1 day'));
                $week_firstdt = date('m/d/Y', strtotime($date->format("m/d/Y")));

                $dueDate = $date->add($interval);
                $date = $dueDate;
                $week_lastdt = $date->format("m/d/Y");

                //if ((strtotime($curr_date) >= strtotime("01/01/" . date("Y"))) && (strtotime($curr_date) < strtotime("01/" . date('d',strtotime(date('Y') . 'W03')) . "/" . date("Y")))) {

                //Need to hard code as year to year condition not working 
                //- If we try to code it more dynamically so it doesn't mess up each new year, we'll need to figure out how to make the logic not calendar year dependant, as the logic is simply to reset every 2 weeks, regardless of what year. If we hard code the "start" date to be 12/29/2020 though, including the year, maybe that would fix it? Unsure.

                if ((strtotime($curr_date) >= strtotime("01/01/" . date("Y"))) && (strtotime($curr_date) < strtotime("01/11/" . date("Y")))) {
                    //$dt = date(date('Y')-1 . '-12-29');
                    //$dttitle = date('m/d/Y', strtotime(date('Y')-1 . '-12-29'));

                    $last_tuesday = strtotime('last tuesday of last month');

                    $dt = date("Y-m-d", $last_tuesday);
                    $dttitle = date('m/d/Y', $last_tuesday);
                } else {
                    if (strtotime($curr_date) >= strtotime($week_firstdt) && strtotime($curr_date) <= strtotime($week_lastdt)) {
                        $dt = date('Y-m-d', strtotime($week_firstdt));
                        $dttitle = date('m/d/Y', strtotime($week_firstdt));
                    }
                }
            }

            //New logic, kept old one
            /*if (date("D") == "Mon"){
	$dt = date("Y-m-d", strtotime('tuesday last week'));  
	$dttitle = date('m/d/Y', strtotime('tuesday last week'));
}else{
	$dt = date("Y-m-d", strtotime('tuesday last week'));  
	$dttitle = date('m/d/Y', strtotime('tuesday last week'));
}	
*/

            $x = 1;
            $arrLeaderboardHunvalley = array();
            $query = "SELECT DISTINCT worker_id FROM loop_timeclock WHERE time_out <> '0000-00-00 00:00:00' and (loop_timeclock.type LIKE '%Production' OR loop_timeclock.type LIKE '%kits' OR loop_timeclock.type LIKE '%achines') AND loop_timeclock.warehouse_id = 18 ";
            if ($_GET["start_date"] != "qqq") {
                $query .= " AND time_in BETWEEN '" . $dt . "'";
            }
            if ($_GET["end_date"] != "qqqq") {
                $query .= " AND NOW()";
            }
            //echo $query . "<br>";
            db();
            $resq = db_query($query);
            //echo "resq - <pre>"; print_r($resq); echo "</pre>";
            while ($rowq = array_shift($resq)) {
                $query = "SELECT name, loop_workers.rate_cost AS RC, SUM(TIME_TO_SEC(TIMEDIFF(time_out,time_in)))/3600 AS DT FROM loop_timeclock LEFT JOIN loop_workers ON loop_timeclock.worker_id = loop_workers.id WHERE loop_timeclock.time_out <> '0000-00-00 00:00:00' and (loop_timeclock.type LIKE '%Production' OR loop_timeclock.type LIKE '%kits' OR loop_timeclock.type LIKE '%achines') AND loop_timeclock.worker_id = " . $rowq["worker_id"];
                if ($_GET["start_date"] != "qqq") {
                    $query .= " AND time_in BETWEEN '" . $dt . "'";
                }
                if ($_GET["end_date"] != "qqqq") {
                    $query .= " AND NOW()";
                }
                //echo $query . "<br>";
                db();
                $res = db_query($query);
                $name = "";
                $hours = 0;
                $hourrate = 0;
                //echo "res - <pre>"; print_r($res); echo "</pre>";
                while ($row = array_shift($res)) {
                    $name = $row["name"];
                    $hourrate = $row["RC"];

                    if ($row["DT"] > 0) {
                        $hours = $row["DT"];
                    }
                    $query = "SELECT rate, production FROM loop_timeclock_production WHERE worker_id =  " . $rowq["worker_id"];
                    if ($_GET["start_date"] != "q") {
                        $query .= " AND date BETWEEN '" . $dt . "'";
                    }
                    if ($_GET["end_date"] != "q") {
                        $query .= " AND NOW()";
                    }
                    //echo $query . "<br>";
                    db();
                    $res = db_query($query);

                    $efficiency = 0;
                    $tot_production = 0;
                    while ($row2 = array_shift($res)) {

                        /**************************************************************************************************************************/
                        /**************************************************************************************************************************/
                        $tot_production_tier = 0;
                        $et_query = "select * from loop_worker_tier where tier = (select emp_tier from loop_workers where id=" . $rowq["worker_id"] . ")";
                        //echo $et_query . "<br>";
                        db();
                        $etres = db_query($et_query);
                        $et_row = array_shift($etres);
                        $emp_tier_value = $et_row["tier_value"];

                        $production_val = $row2["rate"] * $row2["production"];

                        $tot_production_tier = $production_val * $emp_tier_value;
                        $tot_production = $tot_production + number_format(($production_val + $tot_production_tier), 2);

                        /**************************************************************************************************************************/
                        /**************************************************************************************************************************/

                        //echo $tot_production . "<br>";

                        // $tot_production = $row2["P"];
                        //if (($row["DT"] * $row["RC"]) > 0 )
                        //{ 	$efficiency = 100*$row2["P"]/($row["DT"] * $row["RC"]); }
                        //else{	$efficiency = 0; }

                    }

                    if (($row["DT"] * $row["RC"]) > 0) {
                        $efficiency = 100 * $tot_production / ($row["DT"] * $row["RC"]);
                    } else {
                        $efficiency = 0;
                    }
                }
            ?>


            <?php
                //db_query("INSERT INTO loop_timeclock_leaderboard ( `name` , `totalhours` , `efficiency`, production ) VALUES ('" . $name ."','" . $hours ."', '" . $efficiency . "','" . $tot_production . "')",db());
                $arrLeaderboardHunvalley[] = array('efficiency' => isset($efficiency), 'name' => $name, "hourrate" => $hourrate, 'totalhours' => $hours, 'production' => isset($tot_production));
            }
            ?>
            <tr align="middle">
                <td colSpan="7" class="style7">
                    <b>PRODUCTION LEADERBOARD <?php echo $dttitle; ?> - <?php echo date('m/d/Y'); ?></b> &nbsp; <a href="huntvalleywarehouse_leaderboard.php" target="_blank">History</a>
                </td>
            </tr>
            <tr>
                <td style="width: 10" class="style17" align="center">
                    <b>RANK</b>
                </td>
                <td style="width: 190" class="style17" align="center">
                    <b>NAME</b>
                </td>
                <td style="width: 150" class="style17" align="center">
                    <b>TOTAL PROD HOURS</b>
                </td>
                <td style="width: 100" class="style17" align="center">
                    <b>PRODUCTION/HOUR</b>
                </td>
                <td style="width: 80" class="style17" align="center">
                    <b>TOTAL PRODUCTION</b>
                </td>
                <td style="width: 80" class="style17" align="center">
                    <b>MONEY LOST</b>
                </td>
            </tr>
            <?php




            // array_multisort(
            //     array_column($arrLeaderboardHunvalley, 'production'),
            //     SORT_DESC,
            //     array_column($arrLeaderboardHunvalley, 'efficiency'),
            //     SORT_DESC,
            //     array_column($arrLeaderboardHunvalley, 'totalhours'),
            //     SORT_DESC,
            //     array_column($arrLeaderboardHunvalley, 'name'),
            //     SORT_ASC,
            //     $arrLeaderboardHunvalley
            // );
            $production = array_column($arrLeaderboardHunvalley, 'production');
            $efficiency = array_column($arrLeaderboardHunvalley, 'efficiency');
            $totalhours = array_column($arrLeaderboardHunvalley, 'totalhours');
            $name = array_column($arrLeaderboardHunvalley, 'name');

            array_multisort(
                $production,
                SORT_DESC,
                $efficiency,
                SORT_DESC,
                $totalhours,
                SORT_DESC,
                $name,
                SORT_ASC,
                $arrLeaderboardHunvalley
            );

            $x = 1;
            foreach ($arrLeaderboardHunvalley as $arrLeaderboardHunvalleyK => $arrLeaderboardHunvalleyV) {
                if ($arrLeaderboardHunvalleyV["efficiency"] > 99.99) {
                    $color = "<font color=green face=bold>";
                } elseif ($arrLeaderboardHunvalleyV["efficiency"] < 100.00) {
                    $color = "<font color=red face=bold>";
                } else {
                    $color = "<font color=#333333 face=bold>";
                }/**/
            ?>
                <tr vAlign="center">
                    <td bgColor="#e4e4e4" class="style3" align="center">
                        <?php echo $color . $x; ?></font>
                    </td>
                    <td bgColor="#e4e4e4" class="style3" align="left">
                        <?php echo $color .  $arrLeaderboardHunvalleyV["name"]; ?></font>
                    </td>
                    <td bgColor="#e4e4e4" class="style3" align="center">
                        <?php echo $color .  number_format($arrLeaderboardHunvalleyV["totalhours"], 2);
                        ?></font>
                    </td>
                    <td bgColor="#e4e4e4" class="style3" align="center">
                        <?php
                        if ($arrLeaderboardHunvalleyV["totalhours"] > 0) {
                            echo $color . "$" . number_format($arrLeaderboardHunvalleyV["production"] / $arrLeaderboardHunvalleyV["totalhours"], 2);
                        }
                        ?>
                        </font>
                    </td>
                    <td bgColor="#e4e4e4" class="style3" align="center">
                        <?php echo $color . "$" . number_format((float)$arrLeaderboardHunvalleyV["production"], 2);
                        ?>
                        </font>
                    </td>

                    <td bgColor="#e4e4e4" class="style3" align="center">
                        <?php

                        $money_lost = $arrLeaderboardHunvalleyV["production"] - ($arrLeaderboardHunvalleyV["hourrate"] * $arrLeaderboardHunvalleyV["totalhours"]);

                        if ($money_lost > 0) {
                            echo "$0.00";
                        } else {
                            if ($money_lost < 0) {
                                echo $color . "$" . number_format($money_lost, 2);
                            } else {
                                $money_lost = 0;
                                echo "$0.00";
                            }
                            $money_lost_tot = $money_lost_tot + $money_lost;
                        }
                        ?>
                        </font>
                    </td>
                </tr>
            <?php
                $x++;
            }
            ?>
        </table>
        <br>

        <?php

        $sql_pc = "SELECT * FROM `orders_active_ucb_hunt_valley` WHERE ship_status LIKE 'N' ";
        db();
        $result_pc = db_query($sql_pc);
        $count_pc2 = tep_db_num_rows($result_pc);

        $currentdate = new DateTime();
        $prev_date = $currentdate;

        //$sql_pc2 = "SELECT * FROM `orders_by_warehouse_eod` where warehouse_name = 'Hunt Valley' and eod_date = '". date("Y-m-d") ."'";
        //$sql_pc2 = "SELECT orders.orders_id FROM `orders_active_ucb_hunt_valley` inner join orders on orders.orders_id = orders_active_ucb_hunt_valley.orders_id WHERE ship_status LIKE 'Y' and orders.date_purchased BETWEEN '". $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
        $sql_pc2 = "SELECT orders_active_export.orders_id FROM `orders_active_export` WHERE warehouse_id = 11 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
        db();
        $result_pc2 = db_query($sql_pc2);
        $count_pc_tot_for_day2 = 0;
        while ($result_pc_row = array_shift($result_pc2)) {
            //$count_pc_tot_for_day2= $result_pc_row["no_of_kits_shipped"];
            $count_pc_tot_for_day2 = $count_pc_tot_for_day2 + 1;
        }

        ?>
        <table cellSpacing="1" cellPadding="1" border="0" width="200">
            <tr align="middle">
                <td colSpan="4" class="style7" style="height: 16px">
                    <b>PENDING SHIPMENTS</b>
                </td>
            </tr>
            <tr align="middle">
                <td class="style7" style="height: 16px">
                    Warehouse</td>
                <td class="style7" style="height: 16px">
                    Labels to be Printed</td>
                <td class="style7" style="height: 16px">
                    Labels Printed Today</td>
            </tr>

            <tr vAlign="center">
                <td bgColor="#e4e4e4" class="style12" style="width: 10%">Hunt Valley</td>
                <td bgColor="#e4e4e4" class="style12" style="width: 10%">
                    <a href="../ucbdb/pending_shipments.php?tbl=huntvally&fromdash=y&posting=yes" target="_blank"><?php echo $count_pc2; ?></a>
                </td>
                <td bgColor="#e4e4e4" class="style12" style="width: 10%">
                    <a href="../ucbdb/shipments_shipped.php?tbl=huntvally&fromdash=y&posting=yes" target="_blank"><?php echo $count_pc_tot_for_day2; ?></a>
                </td>
                <!-- <td bgColor="#e4e4e4" class="style12" style="width: 10%">
			<input type="button" name="btn_eod_la" id="btn_eod_la" value="Close Day" onclick="eod_popup('hv')"/>
		</td> -->
            </tr>
        </table>

    </td>


    <td valign="top">

        <!--------------------- BEGIN QUICK LINKS  ----------------------------------------------->
        <table cellSpacing="1" cellPadding="1" border="0" width="200">
            <tr align="middle">
                <td class="style7">
                    <b>QUICK LINKS</b>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a href="http://loops.usedcardboardboxes.com/report_timeclock_public.php"><b>VIEW TIMECLOCK</b></a>

                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a href="http://loops.usedcardboardboxes.com/report_timeclock_production_add.php?location_dd=1&show_data=Submit"><b>ENTER
                            PRODUCTION</b></a>

                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a href="http://loops.usedcardboardboxes.com/report_hunt_valley_inventory.php"><b>INVENTORY COUNT
                            SHEET</b></a>

                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a href="http://loops.usedcardboardboxes.com/deletewarehousetransaction.php">REMOVE DUPLICATE
                        TRAILERS</a>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a href="http://loops.usedcardboardboxes.com/inventory_converter.php">INVENTORY CONVERTER</a>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a target="_blank" href="http://loops.usedcardboardboxes.com/TrailerSweep_HV.php"><b><span style="text-transform: uppercase;">trailer sweep</span></b></a>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a target="_blank" href="http://loops.usedcardboardboxes.com/report_inbound_inventory_summary.php?warehouse_id=18"><b><span style="text-transform: uppercase;">Inbound Summary</span></b></a>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a target="_blank" href="http://loops.usedcardboardboxes.com/hr_page.php?warehouse_id=18"><b>WRITE
                            TO HR</b></a>

                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a target="_blank" href="kit_box_report.php?warehouse_id=18"><b>Kit Box Inventory</b></a>

                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a target="_blank" href="report_search_processed_inbound_trailers.php?sorting_warehouse=18"><b>Search Processed
                            Inbound Trailers</b></a>

                </td>
            </tr>

            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a href='canusahershmanwarehouse_1614835009.php' target='_blank'>UCB - Canusa Hershman</a>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <b>Snow Hotline Phone #</b> 410-771-7669<br>

                </td>
            </tr>
            <!--We don't use them anymore <tr>
		<td bgColor="#e4e4e4" class="style12center" >
			<b>Rehrig Pacific Logistics</b><br>
			Eric Chambers<br>
			<a href="http://loops.usedcardboardboxes.com/search_results.php?id=196&proc=View&searchcrit=rehrig&rec_type=Supplier&page=">View Loops Record</a><br>
<a href="mailto:EChambers@Rehrig.com">EChambers@Rehrig.com</a><br>
<a href="addpalletso.php">CREATE NEW PALLET ORDER</a>

			
		</td>
</tr> -->
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <b>Georgetown Paperstock</b><br>
                    Stuart Selis<br>
                    14820 Southlawn Lane<br>
                    Rockville, MD 20850<br>
                    301-762-6990<br>
                    <a href="mailto:service@gtownpaper.com">service@gtownpaper.com</a><br>
                    <a href="addrecycleso.php">CREATE NEW GPS DROP TRAILER TRANSACTION</a><br>
                    <a href="addrecycleso_directship.php">CREATE NEW DIRECT SHIP GPS TRANSACTION</a>


                </td>
            </tr>

        </table>

        <!----------------------- END QUICK LINKS ------------>

    </td>
    </tr>

    </table>


    <!--------------- END PRODUCTION TABLE ---------------->
    <br><br>

    <?php
    $wh_name = "HV";
    ?>
    <table border="0">
        <tr>
            <td valign="top">
                <!--------------- REQUESTED TABLE ---------------->
                <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

                    <tr align="middle">
                        <td colSpan="10" class="style7">
                            <b>Inbound Trailers: Requested, Not Delivered</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:75" class="style17" align="center">
                            <b>DATE REQUEST</b>
                        </td>
                        <td style="width: 75" class="style17" align="center">
                            <b>TRANS #</b>
                        </td>
                        <td style="width: 75" class="style17" align="center">
                            <b>TRAILER #</b>
                        </td>
                        <td class="style5" align="center">
                            <b>SUPPLIER DOCK</b>
                        </td>
                        <td class="style5" align="center">
                            <b>BOL</b>
                        </td>
                        <td style="width:100" valign="middle" class="style16" align="center">
                            <b>REQUESTED BY</b>
                        </td>
                        <td style="width:300" valign="middle" class="style16" align="center">
                            <b> COMPANY </b>
                        </td>
                        <td valign="middle" class="style16" align="center">
                            <b>CONFIRM DELIVERY TO DOCK</b>
                        </td>
                        <td valign="middle" class="style16" align="center">
                            <b>CONFIRM DELIVERY TO LOT</b>
                        </td>
                        <td valign="middle" class="style16" align="center">
                            <b>SENT TO RECYCLING</b>
                        </td>
                    </tr>

                    <?php
                    $query = "Insert into loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
                    $query .= "SELECT loop_transaction.id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE warehouse_id IN ( $warehouse_id_list_str) AND bol_employee LIKE '' AND loop_transaction.pa_warehouse ='' AND pr_recycling = 0 and srt_dockdoors_flg=0 and pr_ucblot = 0 and loop_transaction.ignore = 0";
                    //echo $query . "<br>";
                    db();
                    db_query($query);

                    $query = "SELECT *, loop_transaction.id AS A, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE (loop_transaction.pa_warehouse = 18 or loop_transaction.pa_warehouse = 4146) and loop_transaction.cp_notes = '' AND bol_employee LIKE ''  AND pr_recycling = 0 and pr_ucblot = 0 and srt_dockdoors_flg=0 and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
                    db();
                    $res = db_query($query);
                    //echo $query;
                    while ($row = array_shift($res)) {
                        db();
                        $res_rechk = db_query("Select rec_id from loop_mcc_dash_tobeprc where rec_id = '" . $row["A"] . "'");
                        $rechk = "no";
                        while ($row_rechk = array_shift($res_rechk)) {
                            $rechk = "yes";
                        }

                        if ($rechk == "no") {
                            $query = "Insert into loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
                            $query .= "SELECT '" . $row["A"] . "', '" . str_replace("'", "\'", $row["pr_requestby"]) . "', '" . str_replace("'", "\'", $row["pr_requestdate"]) . "', '" . str_replace("'", "\'", $row["pr_pickupdate"]) . "', '" . str_replace("'", "\'", $row["pr_dock"]) . "', '" . str_replace("'", "\'", $row["pr_trailer"]) . "', '" . str_replace("'", "\'", $row["pa_employee"]) . "', '" . str_replace("'", "\'", $row["bol_filename"]) . "', '" . $row["company_name"] . "', '" . $row["wid"] . "'";
                            //echo $query . "<br>";
                            db();
                            db_query($query);
                        }
                    }

                    $query = "Select * from loop_mcc_dash_tobeprc order by rec_id asc";
                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {
                        $b2bid = 0;
                        $query1 = "Select b2bid from loop_warehouse where id = '" . $row["wid"] . "'";
                        db();
                        $res1 = db_query($query1);
                        while ($row1 = array_shift($res1)) {
                            $b2bid = $row1["b2bid"];
                        }
                        $today = strtotime(date("m/d/Y"));
                        $request_date = strtotime($row["pr_requestdate"]);
                        $diff = ($today - $request_date) / 60 / 60 / 24;
                        //echo $diff."<br>";

                        if ($diff > 5) {
                            $tablerow_color = "#f5dddc";
                            $get_all_red_row_cnt = $get_all_red_row_cnt + 1;
                            $HV_red_row_loop_ids_str = $HV_red_row_loop_ids_str . $row["rec_id"] . ",";
                        } elseif ($diff == 5) {
                            $tablerow_color = "#FFFF99";
                        } elseif ($diff < 5) {
                            $tablerow_color = "#e4e4e4";
                        }

                    ?>
                        <tr align="center">
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["rec_id"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["pr_trailer"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["pr_dock"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php
                                if ($row["bol_filename"] != "") { ?>
                                    <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                                        <font color="blue"><u>View BOL</u></font>
                                    </div>
                                <?php } ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <?php echo $row["pr_requestby"]; ?>
                            </td>

                            <td align="left" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <a target="_blank" href="viewCompany.php?ID=<?php echo $b2bid ?>&show=transactions&warehouse_id=<?php echo $row["wid"] ?>&rec_type=Manufacturer&proc=View&searchcrit=&id=<?php echo $row["wid"] ?>&rec_id=<?php echo $row["rec_id"] ?>&display=seller_view"><?php echo get_nickname_val($row["company_name"], $b2bid); ?></a>
                            </td>
                            <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <div id="dockdoor_<?php echo $row["rec_id"] ?>">
                                    <input type=button value="Delivered to DOCK" style="cursor:pointer;" onclick="fn_delivered_to_dock('<?php echo $row["pr_trailer"]; ?>','<?php echo $row["rec_id"] ?>','false','<?php echo $wh_name; ?>')">
                                </div>

                            </td>
                            <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <input type=button value="Delivered to LOT" style="cursor:pointer;" onclick="confirmationUcblot('<?php echo $row["pr_trailer"]; ?>',<?php echo $row["rec_id"] ?>,'<?php echo $row["pr_dock"]; ?>')">

                            </td>
                            <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <input type=button value="Recycling" style="cursor:pointer;" onclick="confirmationRecycling('<?php echo $row["pr_trailer"]; ?>',<?php echo $row["rec_id"] ?>,'<?php echo $row["pr_dock"]; ?>')">
                            </td>



                        </tr>
                    <?php
                    }

                    $query = "delete from loop_mcc_dash_tobeprc";
                    db();
                    db_query($query);
                    ?>
                </table>
                <!--------------- END REQUESTED TABLE ---------------->
                <br><br>
                <?php
                //Find oldest date
                $query = "SELECT *, loop_transaction.id AS A FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE loop_transaction.ignore = 0 and pa_warehouse = 18 and ((loop_transaction.cp_notes = '' AND pr_ucblot = 1 AND srt_dockdoors_flg=0 AND bol_employee LIKE '') or (srt_dockdoors_flg =1 AND ucbunloaded_flg=0) or (ucbunloaded_flg=1 AND (sort_entered = 0 or usr_file LIKE ''))) ORDER BY loop_transaction.ID ASC";

                //echo $query;
                db();
                $res = db_query($query);
                while ($rowflg = array_shift($res)) {
                    $ucblot = "01/01/1970";
                    $ucbdocdoor = "01/01/1970";
                    $ucbunloaded = "01/01/1970";
                    if ($rowflg["pr_ucblot_dt"] != "" && $rowflg["pr_ucblot_dt"] != "0000-00-00 00:00:00") {
                        $ucblot = date("m/d/Y", strtotime($rowflg["pr_ucblot_dt"]));
                    }
                    if ($rowflg["srt_ucbdockdoor_dt"] != "" && $rowflg["srt_ucbdockdoor_dt"] != "0000-00-00 00:00:00") {
                        $ucbdocdoor = date("m/d/Y", strtotime($rowflg["srt_ucbdockdoor_dt"]));
                    }
                    if ($rowflg["ucbunloaded_dt"] != "" && $rowflg["ucbunloaded_dt"] != "0000-00-00 00:00:00") {
                        $ucbunloaded = date("m/d/Y", strtotime($rowflg["ucbunloaded_dt"]));
                    }
                    // 
                    //echo $rowflg["A"]."=".$ucblot."--".$ucbdocdoor."--".$ucbunloaded."<br>";
                    //
                    if (($ucblot != "01/01/1970") && ($ucbdocdoor == "01/01/1970") && ($ucbunloaded == "01/01/1970")) {
                        $oldestdate = $ucblot;
                    } elseif (($ucblot == "01/01/1970") && ($ucbdocdoor != "01/01/1970") && ($ucbunloaded == "01/01/1970")) {
                        $oldestdate = $ucbdocdoor;
                    } elseif (($ucblot == "01/01/1970") && ($ucbdocdoor == "01/01/1970") && ($ucbunloaded != "01/01/1970")) {
                        $oldestdate = $ucbunloaded;
                    } elseif (($ucblot != "01/01/1970") && ($ucbdocdoor != "01/01/1970") && ($ucbunloaded == "01/01/1970")) {

                        $oldestdate1 = strtotime($ucblot);
                        $oldestdate2 = strtotime($ucbdocdoor);
                        if ($oldestdate1 < $oldestdate2) {
                            $oldestdate = date("m/d/Y", strtotime($ucblot));
                        } else {
                            $oldestdate = date("m/d/Y", strtotime($ucbdocdoor));
                        }
                    } elseif (($ucblot != "01/01/1970") && ($ucbdocdoor == "01/01/1970") && ($ucbunloaded != "01/01/1970")) {

                        $oldestdate1 = strtotime($ucblot);
                        $oldestdate2 = strtotime($ucbunloaded);
                        if ($oldestdate1 < $oldestdate2) {
                            $oldestdate = date("m/d/Y", strtotime($ucblot));
                        } else {
                            $oldestdate = date("m/d/Y", strtotime($ucbunloaded));
                        }
                    } elseif (($ucblot == "01/01/1970") && ($ucbdocdoor != "01/01/1970") && ($ucbunloaded != "01/01/1970")) {

                        $oldestdate1 = strtotime($ucbdocdoor);
                        $oldestdate2 = strtotime($ucbunloaded);
                        if ($oldestdate1 < $oldestdate2) {
                            $oldestdate = date("m/d/Y", strtotime($ucbdocdoor));
                        } else {
                            $oldestdate = date("m/d/Y", strtotime($ucbunloaded));
                        }
                    } elseif (($ucblot != "01/01/1970") && ($ucbdocdoor != "01/01/1970") && ($ucbunloaded != "01/01/1970")) {
                        $oldestdate1 = strtotime($ucblot);
                        $oldestdate2 = strtotime($ucbdocdoor);
                        $oldestdate3 = strtotime($ucbunloaded);
                        $old_dt = min($oldestdate1, $oldestdate2, $oldestdate3);
                        $oldestdate = date("m/d/Y", $old_dt);
                    }
                    $oldest_date .= $oldestdate . ",";
                    $trs_id .= $rowflg["A"] . ",";
                    //
                }
                $oldest_date = rtrim($oldest_date, ", ");
                $trns_id = rtrim($trs_id, ", ");
                //echo $trns_id . " <br><br>" . $oldest_date;
                //
                ?>
                <!--------------- UCB Lot TABLE ---------------->
                <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

                    <tr align="center">
                        <td colSpan="10" class="style7">
                            <b>Inbound Trailers: Delivered to Lot, Needs Put in Dock Door</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:75" class="style17" align="center">
                            <b>DATE REQUEST</b>
                        </td>
                        <td style="width: 75" class="style17" align="center">
                            <b>TRANS #</b>
                        </td>
                        <td style="width: 100" class="style17" align="center">
                            <b>TRAILER #</b>
                        </td>
                        <td class="style5" align="center">
                            <b>SUPPLIER DOCK</b>
                        </td>
                        <td class="style5" align="center">
                            <b>BOL</b>
                        </td>
                        <td style="width:100" valign="middle" class="style16" align="center">
                            <b>REQUESTED BY</b>
                        </td>
                        <td style="width:300" valign="middle" class="style16" align="center">
                            <b>COMPANY</b>
                        </td>
                        <td valign="middle" class="style16" align="center">
                            <b>CONFIRM DELIVERY TO DOCK</b>
                        </td>
                        <td valign="middle" class="style16" align="center">
                            <b>UNDO</b>
                        </td>
                    </tr>

                    <?php
                    //$query = "Insert into loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
                    //$query .= "SELECT loop_transaction.id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE warehouse_id IN ( $warehouse_id_list_str2) AND srt_dockdoors_flg=0 AND pr_ucblot = 1 AND bol_employee LIKE '' and loop_transaction.ignore = 0";
                    //db_query($query,db());

                    $query = "SELECT *, loop_transaction.id AS A, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE loop_transaction.pa_warehouse = 18 and loop_transaction.cp_notes = '' AND srt_dockdoors_flg=0 AND bol_employee LIKE '' AND pr_ucblot = 1 and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {
                        db();
                        $res_rechk = db_query("Select rec_id from loop_mcc_dash_tobeprc where rec_id = '" . $row["A"] . "'");
                        $rechk = "no";
                        while ($row_rechk = array_shift($res_rechk)) {
                            $rechk = "yes";
                        }

                        if ($rechk == "no") {
                            $query = "Insert into loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
                            $query .= "SELECT '" . $row["A"] . "', '" . str_replace("'", "\'", $row["pr_requestby"]) . "', '" . str_replace("'", "\'", $row["pr_requestdate"]) . "', '" . str_replace("'", "\'", $row["pr_pickupdate"]) . "', '" . str_replace("'", "\'", $row["pr_dock"]) . "', '" . str_replace("'", "\'", $row["pr_trailer"]) . "', '" . str_replace("'", "\'", $row["pa_employee"]) . "', '" . str_replace("'", "\'", $row["bol_filename"]) . "', '" . str_replace("'", "\'", $row["company_name"]) . "', " . $row["wid"];
                            db();
                            db_query($query);
                        }
                    }

                    $query = "Select * from loop_mcc_dash_tobeprc order by rec_id asc";
                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {

                        $b2bid = 0;
                        $query1 = "Select b2bid from loop_warehouse where id = '" . $row["wid"] . "'";
                        db();
                        $res1 = db_query($query1);
                        while ($row1 = array_shift($res1)) {
                            $b2bid = $row1["b2bid"];
                        }
                        //$oldest_date
                        $date_arr = explode(",", $oldest_date);
                        $trns_arr = explode(",", $trns_id);
                        for ($i = 0; $i < count($trns_arr); $i++) {
                            if ($trns_arr[$i] == $row["rec_id"]) {
                                if ($date_arr[$i] != "") {
                                    $ucblot_dt = strtotime($date_arr[$i]);
                                    $today = strtotime(date("m/d/Y"));
                                    $diff = ($today - $ucblot_dt) / 60 / 60 / 24;
                                    //echo $diff."<br>";

                                    if ($diff > 3) {
                                        $tablerow_color = "#f5dddc";
                                        $get_all_red_row_cnt = $get_all_red_row_cnt + 1;
                                        $HV_red_row_loop_ids_str = $HV_red_row_loop_ids_str . $row["rec_id"] . ",";
                                    } elseif ($diff == 3) {
                                        $tablerow_color = "#FFFF99";
                                    } elseif ($diff < 3) {
                                        $tablerow_color = "#e4e4e4";
                                    }
                                }
                            }
                        }
                    ?>
                        <tr align="center">
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["rec_id"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["pr_trailer"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["pr_dock"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php
                                if ($row["bol_filename"] != "") { ?>
                                    <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                                        <font color="blue"><u>View BOL</u></font>
                                    </div>
                                <?php } ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <?php echo $row["pr_requestby"]; ?>
                            </td>
                            <td align="left" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <a target="_blank" href="viewCompany.php?ID=<?php echo $b2bid ?>&show=transactions&warehouse_id=<?php echo $row["wid"] ?>&rec_type=Manufacturer&proc=View&searchcrit=&id=<?php echo $row["wid"] ?>&rec_id=<?php echo $row["rec_id"] ?>&display=seller_view"><?php echo get_nickname_val($row["company_name"], $b2bid); ?></a>
                            </td>
                            <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <div id="dockdoor_<?php echo $row["rec_id"] ?>">
                                    <input type=button value="Delivered to DOCK" style="cursor:pointer;" onclick="fn_delivered_to_dock('<?php echo $row["pr_trailer"]; ?>','<?php echo $row["rec_id"] ?>','false', '<?php echo $wh_name; ?>')">
                                </div>
                            </td>
                            <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <input type=button value="UnDo" style="cursor:pointer;" onclick="undoucblot('<?php echo $row["pr_trailer"]; ?>',<?php echo $row["rec_id"] ?>,'<?php echo $row["pr_dock"]; ?>')">
                            </td>

                        </tr>
                    <?php
                    }

                    $query = "delete from loop_mcc_dash_tobeprc";
                    db();
                    db_query($query);
                    ?>
                </table>
                <!--------------- END UCBlot TABLE ---------------->

                <br><Br>


                <!--------------- RECYCLING TABLE ---------------->
                <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

                    <tr align="middle">
                        <td colspan="10" class="style7">
                            <b>Inbound Trailers: Delivered Directly to Recycler, Pending Receipt and Sort Report</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:75" class="style17" align="center">
                            <b>DATE REQUEST</b>
                        </td>
                        <td style="width: 75" class="style17" align="center">
                            <b>TRANS #</b>
                        </td>
                        <td style="width:75" class="style17" align="center">
                            <b>TRAILER #</b>
                        </td>
                        <td class="style5" align="center">
                            <b>SUPPLIER DOCK</b>
                        </td>
                        <td class="style5" align="center">
                            <b>BOL</b>
                        </td>
                        <td style="width:100" valign="middle" class="style16" align="center">
                            <b>REQUESTED BY</b>
                        </td>
                        <td style="width:300" valign="middle" class="style16" align="center">
                            <b> COMPANY </b>
                        </td>
                        <td valign="middle" class="style16" align="center">
                            <b>RECYCLER RECEIVED</b>
                        </td>
                        <td valign="middle" class="style16" align="center">
                            <b>UNDO</b>
                        </td>
                    </tr>

                    <?php
                    $query = "Insert into loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
                    $query .= "SELECT loop_transaction.id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE warehouse_id IN ( $warehouse_id_list_str2) AND pr_recycling = 1 AND bol_employee LIKE '' AND ucbunloaded_flg=0 and loop_transaction.ignore = 0";
                    db();
                    db_query($query);

                    $query = "SELECT *, loop_transaction.id AS A, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE loop_transaction.pa_warehouse = 18 and loop_transaction.cp_notes = '' AND bol_employee LIKE '' AND pr_recycling = 1 and loop_transaction.ignore = 0 AND ucbunloaded_flg=0 ORDER BY loop_transaction.ID ASC";
                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {
                        db();
                        $res_rechk = db_query("Select rec_id from loop_mcc_dash_tobeprc where rec_id = '" . $row["A"] . "'");
                        $rechk = "no";
                        while ($row_rechk = array_shift($res_rechk)) {
                            $rechk = "yes";
                        }

                        if ($rechk == "no") {
                            $query = "Insert into loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
                            $query .= "SELECT '" . $row["A"] . "', '" . $row["pr_requestby"] . "', '" . $row["pr_requestdate"] . "', '" . $row["pr_pickupdate"] . "', '" . $row["pr_dock"] . "', '" . $row["pr_trailer"] . "', '" . $row["pa_employee"] . "', '" . $row["bol_filename"] . "', '" . $row["company_name"] . "', '" . $row["wid"] . "'";
                            db();
                            db_query($query);
                        }
                    }

                    $query = "Select * from loop_mcc_dash_tobeprc order by rec_id asc";
                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {

                        $b2bid = 0;
                        $query1 = "Select b2bid from loop_warehouse where id = '" . $row["wid"] . "'";
                        db();
                        $res1 = db_query($query1);
                        while ($row1 = array_shift($res1)) {
                            $b2bid = $row1["b2bid"];
                        }

                        $today = strtotime(date("m/d/Y"));
                        $request_date = strtotime($row["pr_requestdate"]);
                        $diff = ($today - $request_date) / 60 / 60 / 24;
                        //echo $diff."<br>";

                        if ($diff > 14) {
                            $tablerow_color = "#f5dddc";
                            $get_all_red_row_cnt = $get_all_red_row_cnt + 1;
                            $HV_red_row_loop_ids_str = $HV_red_row_loop_ids_str . $row["rec_id"] . ",";
                        } elseif ($diff == 14) {
                            $tablerow_color = "#FFFF99";
                        } elseif ($diff < 14) {
                            $tablerow_color = "#e4e4e4";
                        }
                    ?>
                        <tr align="center">
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["rec_id"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["pr_trailer"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["pr_dock"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php
                                if ($row["bol_filename"] != "") { ?>
                                    <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                                        <font color="blue"><u>View BOL</u></font>
                                    </div>
                                <?php } ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <?php echo $row["pr_requestby"]; ?>
                            </td>
                            <td align="left" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <a target="_blank" href="viewCompany.php?ID=<?php echo $b2bid ?>&show=transactions&warehouse_id=<?php echo $row["wid"] ?>&rec_type=Manufacturer&proc=View&searchcrit=&id=<?php echo $row["wid"] ?>&rec_id=<?php echo $row["rec_id"] ?>&display=seller_view"><?php echo get_nickname_val($row["company_name"], $b2bid); ?></a>
                            </td>
                            <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">


                                <input type=button value="Delivered" id="btnDelivered<?php echo $row["rec_id"] ?>" style="cursor:pointer;" onclick="confirmationUnloaded('<?php echo $row["pr_trailer"]; ?>','<?php echo $row["rec_id"] ?>','<?php echo $row["pr_dock"] ?>')">
                            </td>
                            <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <input type=button value="UnDo" style="cursor:pointer;" onclick="undorecycling('<?php echo $row["pr_trailer"]; ?>',<?php echo $row["rec_id"] ?>,'<?php echo $row["pr_dock"]; ?>')">
                            </td>

                        </tr>
                    <?php
                    }

                    $query = "delete from loop_mcc_dash_tobeprc";
                    db();
                    db_query($query);
                    ?>
                </table>
                <!--------------- END Recycling TABLE ---------------->


            </td>

            <td colspan="2" align="left" valign="top">
                <!--In dock door action -->
                <div id="indockdoor">
                    <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

                        <tr align="middle">
                            <td colSpan="10" class="style7">
                                <b>Inbound Trailers: In Dock Door, Not Unloaded</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:75" class="style17" align="center">
                                <b>UCB DOCK</b>
                            </td>
                            <td style="width:75" class="style17" align="center">
                                <b>DATE REQUEST</b>
                            </td>
                            <td style="width: 75" class="style17" align="center">
                                <b>TRANS #</b>
                            </td>
                            <td style="width: 100" class="style17" align="center">
                                <b>TRAILER #</b>
                            </td>
                            <td class="style5" align="center">
                                <b>SUPPLIER DOCK</b>
                            </td>
                            <td class="style5" align="center">
                                <b>BOL</b>
                            </td>
                            <td style="width:300" valign="middle" class="style16" align="center">
                                <b> COMPANY </b>
                            </td>
                            <td valign="middle" class="style16" align="center">
                                <b>PRINT SORT REPORT</b>
                            </td>
                            <td valign="middle" class="style16" align="center">
                                <b>CONFIRM UNLOADED</b>
                            </td>
                            <td valign="middle" class="style16" align="center">
                                <b>UNDO</b>
                            </td>
                        </tr>

                        <?php
                        $query = "SELECT *, loop_warehouse.warehouse_name AS CN, loop_warehouse.id as wid , loop_transaction.id AS LTID FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE (pa_warehouse = 18) and srt_dockdoors_flg =1 AND ucbunloaded_flg=0 and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
                        db();
                        $res = db_query($query);
                        while ($row = array_shift($res)) {
                            $rec_id = $row["LTID"];
                            $b2bid = 0;
                            $query1 = "Select b2bid from loop_warehouse where id = '" . $row["wid"] . "'";
                            db();
                            $res1 = db_query($query1);
                            while ($row1 = array_shift($res1)) {
                                $b2bid = $row1["b2bid"];
                            }

                            $date_arr = explode(",", $oldest_date);
                            $trns_arr = explode(",", $trns_id);
                            for ($i = 0; $i < count($trns_arr); $i++) {
                                if ($trns_arr[$i] == $row["LTID"]) {
                                    if ($date_arr[$i] != "") {
                                        $ucblot_dt = strtotime($date_arr[$i]);
                                        $today = strtotime(date("m/d/Y"));
                                        $diff = ($today - $ucblot_dt) / 60 / 60 / 24;
                                        //echo $diff."<br>";

                                        if ($diff > 3) {
                                            $tablerow_color = "#f5dddc";
                                            $get_all_red_row_cnt = $get_all_red_row_cnt + 1;
                                            $HV_red_row_loop_ids_str = $HV_red_row_loop_ids_str . $row["LTID"] . ",";
                                        } elseif ($diff == 3) {
                                            $tablerow_color = "#FFFF99";
                                        } elseif ($diff < 3) {
                                            $tablerow_color = "#e4e4e4";
                                        }
                                    }
                                }
                            }

                        ?>
                            <tr align="center">
                                <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                    <?php echo $row["srt_dock_doors"]; ?>
                                    <br>
                                    <div id="dockdoor_<?php echo $row["LTID"] ?>">
                                        <a href="#" onClick="fn_delivered_to_dock('<?php echo $row["pr_trailer"]; ?>','<?php echo $row["LTID"] ?>','true','<?php echo $wh_name; ?>')">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                                <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                    <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
                                <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                    <?php echo $row["LTID"]; ?>
                                </td>
                                <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                    <?php echo $row["pr_trailer"]; ?>
                                </td>
                                <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                    <?php echo $row["pr_dock"]; ?>
                                </td>
                                <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                    <?php
                                    if ($row["bol_filename"] != "") { ?>
                                        <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                                            <font color="blue"><u>View BOL</u></font>
                                        </div>
                                    <?php } ?>
                                </td>

                                <td align="left" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                    <a target="_blank" href="viewCompany.php?ID=<?php echo $b2bid ?>&show=transactions&warehouse_id=<?php echo $row["wid"] ?>&rec_type=Manufacturer&proc=View&searchcrit=&id=<?php echo $row["wid"] ?>&rec_id=<?php echo $rec_id ?>&display=seller_view"><?php echo get_nickname_val($row["company_name"], $b2bid); ?></a>
                                </td>
                                <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                    <?php if ($row["manulasortrep_print"] == "Y") { ?>
                                        <input style="cursor:pointer;" type="button" value="Re-Print Report" onClick="printsortrep('<?php echo $row["LTID"] ?>' , '<?php echo $row["manulasortrep_print"]; ?>')">
                                    <?php } else { ?> <input type="button" value="Print Report" onClick="printsortrep('<?php echo $row["LTID"] ?>' , '<?php echo $row["manulasortrep_print"]; ?>')">
                                    <?php } ?>
                                </td>
                                <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                    <input type=button value="UNLOADED" id="btnDelivered<?php echo $rec_id ?>" style="cursor:pointer;" onclick="confirmationUnloaded_new('<?php echo $row["pr_trailer"]; ?>','<?php echo $rec_id ?>','<?php echo $row["pr_dock"] ?>','18')">
                                </td>
                                <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                    <input type=button value="UnDo" style="cursor:pointer;" onclick="undoucbdockdoor('<?php echo $row["pr_trailer"]; ?>','<?php echo $rec_id ?>')">
                                </td>

                            </tr>
                        <?php
                        }
                        //
                        ?>
                    </table>
                </div>
                <!--End in dock door action-->
                <br><br>
                <!------------------New Unloaded table------------------>
                <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

                    <tr align="middle">
                        <td class="style7" colSpan="11">
                            <b>Inbound Trailers: Unloaded, Not Processed (Sorted or Entered)</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 75" class="style17" align="center">
                            <b>Area Unloaded</b>
                        </td>
                        <td style="width: 75" class="style17" align="center">
                            <b>DATE REQUEST</b>
                        </td>
                        <td style="width: 75" class="style17" align="center">
                            <b>TRANS #</b>
                        </td>
                        <td style="width: 75" class="style17" align="center">
                            <b>TRAILER #</b>
                        </td>
                        <td class="style5" align="center">
                            <b>SUPPLIER DOCK</b>
                        </td>
                        <td class="style5" align="center">
                            <b>BOL</b>
                        </td>
                        <td style="width: 350" class="style16" align="center">
                            <b>COMPANY</b>
                        </td>
                        <td style="width: 100" class="style16" align="center">
                            <b>PRINT SORT REPORT</b>
                        </td>
                        <td valign="middle" style="width: 100" class="style16" align="center">
                            <b>ENTER SORT REPORT</b>
                        </td>
                        <td valign="middle" style="width: 100" class="style16" align="center">
                            <b>UNDO</b>
                        </td>
                    </tr>
                    <script LANGUAGE="JavaScript">
                        function printsortrep(id, printstatus) {
                            if (printstatus == "Y") {
                                var answer = confirm("Report already printed. Do you wish to re-print?");
                                if (answer) {
                                    window.location = "sortreport2.php?rec_id=" + id;
                                }
                            } else {
                                window.location = "sortreport2.php?rec_id=" + id;
                            }
                        }

                        function PrintPickListRep(id, printstatus) {
                            if (printstatus == "Y") {
                                var answer = confirm("Report already printed. Do you wish to re-print?");
                                if (answer) {
                                    window.location = "picklist.php?rec_id=" + id;
                                }
                            } else {
                                window.location = "picklist.php?rec_id=" + id;
                            }
                        }

                        function PrintPickListRep_so(id, printstatus) {
                            if (printstatus == "Y") {
                                var answer = confirm("Report already printed. Do you wish to re-print?");
                                if (answer) {
                                    window.location = "picklist.php?rec_id=" + id;
                                }
                            } else {
                                window.location = "picklist.php?rec_id=" + id;
                            }
                        }

                        function undodelivery(trailerno, recid, dockno) {
                            var answer = confirm("Do you wish to Undo the Confirm Delivery of Trailer #" + trailerno + "?")
                            if (answer) {
                                window.location =
                                    "<?php echo huntvalleywarehousepage_test() ?>?action=undodelivery&conf_id=" + recid +
                                    "&trailer_no=" + trailerno + "&dock=" + dockno;
                            } else {
                                alert("Request Cancelled");
                            }
                        }

                        function undorecycling(trailerno, recid, dockno) {
                            var answer = confirm("Do you wish to Undo the RECYCLING Flag of Trailer #" + trailerno + "?")
                            if (answer) {
                                window.location =
                                    "<?php echo huntvalleywarehousepage_test() ?>?action=undorecycling&conf_id=" + recid +
                                    "&trailer_no=" + trailerno + "&dock=" + dockno;
                            } else {
                                alert("Request Cancelled");
                            }
                        }

                        function undoucblot(trailerno, recid, dockno) {
                            var answer = confirm("Do you wish to Undo the UCBLot Flag of Trailer #" + trailerno + "?")
                            if (answer) {
                                window.location =
                                    "<?php echo huntvalleywarehousepage_test() ?>?action=undoucblot&conf_id=" +
                                    recid + "&trailer_no=" + trailerno + "&dock=" + dockno;
                            } else {
                                alert("Request Cancelled");
                            }
                        }

                        function undoucbdockdoor(trailerno, recid) {
                            var answer = confirm("Do you wish to Undo the UCBDockDoor Flag of Trailer #" + trailerno + "?")
                            if (answer) {

                                window.location =
                                    "<?php echo huntvalleywarehousepage_test() ?>?action=undoucbdockdoor&conf_id=" + recid +
                                    "&trailer_no=" + trailerno;
                            } else {
                                alert("Request Cancelled");
                            }
                        }
                    </script>

                    <?php
                    $query = "SELECT *, loop_warehouse.warehouse_name AS CN, loop_warehouse.id as wid , loop_transaction.id AS LTID FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE (pa_warehouse = 18) and pa_pickupdate <> '' AND ucbunloaded_flg=1  AND (sort_entered = 0 or usr_file LIKE '') AND transaction_date > '2010-10-28' and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {

                        $date_arr = explode(",", $oldest_date);
                        $trns_arr = explode(",", $trns_id);
                        for ($i = 0; $i < count($trns_arr); $i++) {
                            if ($trns_arr[$i] == $row["LTID"]) {
                                //echo $trns_arr[$i];
                                if ($date_arr[$i] != "") {
                                    $ucblot_dt = strtotime($date_arr[$i]);
                                    $today = strtotime(date("m/d/Y"));
                                    $diff = ($today - $ucblot_dt) / 60 / 60 / 24;
                                    //echo $diff."<br>";

                                    if ($diff > 3) {
                                        $tablerow_color = "#f5dddc";
                                        $get_all_red_row_cnt = $get_all_red_row_cnt + 1;
                                        $HV_red_row_loop_ids_str = $HV_red_row_loop_ids_str . $row["LTID"] . ",";
                                    } elseif ($diff == 3) {
                                        $tablerow_color = "#FFFF99";
                                    } elseif ($diff < 3) {
                                        $tablerow_color = "#e4e4e4";
                                    }
                                }
                            }
                        }
                    ?>
                        <tr align="center">
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["area_unloaded"]; ?></td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["LTID"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["pr_trailer"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php echo $row["pr_dock"]; ?>
                            </td>
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3" align="center">
                                <?php
                                if ($row["bol_filename"] != "") { ?>
                                    <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                                        <font color="blue"><u>View BOL</u></font>
                                    </div>
                                <?php } ?>
                            </td>
                            <td align="left" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <a target="_blank" href="viewCompany.php?ID=<?php echo $row["b2bid"] ?>&show=transactions&warehouse_id=<?php echo $row["wid"] ?>&rec_type=Manufacturer&proc=View&searchcrit=&id=<?php echo $row["wid"] ?>&rec_id=<?php echo $row["LTID"] ?>&display=seller_view"><?php echo get_nickname_val($row["company_name"], $row["b2bid"]); ?></a>
                            </td>
                            <!-- Added by Mooneem 07-14-12  -->
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <?php if ($row["manulasortrep_print"] == "Y") { ?>
                                    <input style="cursor:pointer;" type="button" value="Re-Print Report" onClick="printsortrep('<?php echo $row["LTID"] ?>' , '<?php echo $row["manulasortrep_print"]; ?>')">
                                <?php } else { ?>
                                    <input type="button" value="Print Report" onClick="printsortrep('<?php echo $row["LTID"] ?>' , '<?php echo $row["manulasortrep_print"]; ?>')">
                                <?php } ?>
                            </td> <!-- Added by Mooneem 07-14-12  -->
                            <td bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <INPUT style="cursor:pointer;" type="button" value="Enter Sort Report" onClick="window.open('http://loops.usedcardboardboxes.com/search_results.php?warehouse_id=<?php echo  $row["warehouse_id"]; ?>&amp;rec_type=Manufacturer&amp;proc=View&amp;searchcrit=&amp;id=<?php echo  $row["warehouse_id"]; ?>&amp;rec_id=<?php echo  $row["LTID"]; ?>&amp;display=seller_sort')">
                                <!-- Email davidkrasnow@usedcardboardboxes.com -->
                            </td>
                            <td align="center" bgColor="<?php echo isset($tablerow_color) ?>" class="style3">
                                <input type=button value="UnDo" style="cursor:pointer;" onclick="undodelivery('<?php echo $row["pr_trailer"]; ?>',<?php echo $row["LTID"] ?>,'<?php echo $row["pr_dock"]; ?>')">
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <!------------------End New Unloaded table-------------->
                <br><br>
            </td>
        </tr>
    </table>


    <!--------------- BEGIN SALES ORDER TABLE ---------------->

    <br><br>
    <table>
        <tr>

            <td valign="top">

                <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

                    <tr align="middle">
                        <td class="style7" colSpan="6">
                            <b>Freight Booking</b>
                        </td>
                    </tr>

                    <tr>
                        <td class="style17" align="center">
                            <b>Loop ID</b>
                        </td>
                        <td class="style17" align="center">
                            <b>Type</b>
                        </td>
                        <td class="style17" align="center">
                            <b>Company Name</b>
                        </td>
                        <td class="style17" align="center">
                            <b>Date</b>
                        </td>
                        <td class="style17" align="center">
                            <b>Time</b>
                        </td>
                        <td class="style17" align="center">
                            <b>PRINT</b>
                        </td>
                    </tr>
                    <?php

                    $todays_dt = date("Y-m-d");
                    $next_date = date('Y-m-d', strtotime($todays_dt . ' + 4 days'));

                    $query_2 = "SELECT warehouse_id,loop_transaction.pa_delivery_date, loop_transaction.pa_delivery_time, loop_warehouse.company_name ,loop_transaction.id AS A FROM loop_transaction";
                    $query_2 .= " INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id";
                    $query_2 .= " WHERE warehouse_id IN ( $warehouse_id_list_str3) AND bol_employee LIKE ''";
                    $query_2 .= " and (pa_delivery_date >='" . $todays_dt . "' and pa_delivery_date <= '" . $next_date . "')";
                    $query_2 .= " and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
                    //echo $query_2."<br>";
                    db();
                    $res_2 = db_query($query_2);
                    while ($row_2 = array_shift($res_2)) {
                        $ins_qry = "INSERT INTO huntvalley_temp (`type` , `trid` , `warehousename` , `date` , `time` ,`picklist_print`,`wid`) VALUES ('Inbound', '" . $row_2["A"] . "', '" . str_replace("'", "\'", $row_2["company_name"]) . "', '" . $row_2["pa_delivery_date"] . "', '" . $row_2["pa_delivery_time"] . "','N','" . $row_2["warehouse_id"] . "')";
                        db();
                        $ins_res = db_query($ins_qry);
                        //echo "1:".$ins_qry."<br>";
                    }

                    $query_3 = "SELECT warehouse_id,loop_transaction.pa_delivery_date, loop_transaction.pa_delivery_time, loop_warehouse.company_name ,loop_transaction.id AS A FROM loop_transaction";
                    $query_3 .= " INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id";
                    $query_3 .= " WHERE warehouse_id IN ( $warehouse_id_list_str3) AND bol_employee LIKE ''";
                    $query_3 .= " and pa_delivery_date < '" . $todays_dt . "'";
                    $query_3 .= " and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
                    //echo $query_3."<br>";	
                    db();
                    $res_3 = db_query($query_3);
                    while ($row_3 = array_shift($res_3)) {
                        $ins_qry = "INSERT INTO huntvalley_temp (`type` , `trid` , `warehousename` , `date` , `time`,`picklist_print`,`wid`) VALUES ('Inbound','" . $row_3["A"] . "', '" . str_replace("'", "\'", $row_3["company_name"]) . "', '" . $row_3["pa_delivery_date"] . "', '" . $row_3["pa_delivery_time"] . "','N','" . $row_3["warehouse_id"] . "')";
                        db();
                        $ins_res = db_query($ins_qry);
                        //echo "1:".$ins_qry."<br>";
                    }

                    $query = "SELECT loop_warehouse.warehouse_name AS warehousename,";
                    $query .= " loop_transaction_buyer.warehouse_id AS wid , loop_transaction_buyer.id AS trid, loop_transaction_freight.*, ";
                    $query .= " loop_transaction_buyer.picklist_print FROM loop_transaction_buyer";
                    $query .= " INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id";
                    $query .= " INNER JOIN loop_salesorders ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id";
                    $query .= " INNER JOIN loop_transaction_freight ON loop_transaction_buyer.id = loop_transaction_freight.trans_rec_id";
                    $query .= " WHERE loop_transaction_buyer.so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0";
                    $query .= " AND loop_transaction_buyer.bol_signed_uploaded = 0";
                    $query .= " AND loop_transaction_buyer.id > 67 and location_warehouse_id = 18";
                    $query .= " and (loop_transaction_freight.date >='" . $todays_dt . "' and loop_transaction_freight.date <= '" . $next_date . "')";
                    $query .= " group by trid order by loop_transaction_freight.date ASC";
                    //echo $query."<br>";	
                    db();
                    $res = db_query($query);
                    while ($rows = array_shift($res)) {
                        $ins_qry_1 = "INSERT INTO huntvalley_temp (`type` , `trid` , `wid` , `warehousename` , `date` , `time` , `picklist_print`) VALUES ('Outbound','" . $rows["trid"] . "', '" . $rows["wid"] . "', '" . str_replace("'", "\'", $rows["warehousename"]) . "', '" . $rows["date"] . "', '" . $rows["time"] . "', '" . $rows["picklist_print"] . "')";
                        db();
                        $ins_res_1 = db_query($ins_qry_1);
                        //echo "2:".$ins_qry_1."<br>";
                    }


                    //exit;

                    $query_1 = "SELECT loop_warehouse.warehouse_name AS warehousename,";
                    $query_1 .= " loop_transaction_buyer.warehouse_id AS wid , loop_transaction_buyer.id AS trid, ";
                    $query_1 .= " loop_transaction_buyer.picklist_print FROM loop_transaction_buyer";
                    $query_1 .= " INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id";
                    $query_1 .= " INNER JOIN loop_salesorders ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id";
                    $query_1 .= " WHERE loop_transaction_buyer.so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0";
                    $query_1 .= " AND loop_transaction_buyer.bol_signed_uploaded = 0";
                    $query_1 .= " AND loop_transaction_buyer.id > 67 and location_warehouse_id = 18 ";
                    $query_1 .= " group by trid";
                    //echo $query_1."<br>";	
                    db();
                    $res_1 = db_query($query_1);
                    while ($row_1 = array_shift($res_1)) {
                        $query_1n = "select * from loop_transaction_freight where trans_rec_id = '" . $row_1["trid"] . "' and (month(loop_transaction_freight.date) <= " . date('m') . " and day(loop_transaction_freight.date) < " . date('d') . " and year(loop_transaction_freight.date) <= " . date('Y') . " )";
                        db();
                        $res_1n = db_query($query_1n);
                        while ($row_1n = array_shift($res_1n)) {
                            $ins_qry_2 = "INSERT INTO huntvalley_temp (`type` , `trid` , `wid` , `warehousename` , `date` , `time` , `picklist_print`) VALUES ('Outbound','" . $row_1["trid"] . "', '" . $row_1["wid"] . "', '" . str_replace("'", "\'", $row_1["warehousename"]) . "', '" . $row_1n["date"] . "', '" . $row_1n["time"] . "', '" . $row_1n["picklist_print"] . "')";
                            db();
                            $ins_res_2 = db_query($ins_qry_2);
                        }
                        //echo "4:".$ins_qry_2."<br>";
                    }

                    $qry = "select * from huntvalley_temp order by date asc";
                    //echo $qry."<br>";	
                    db();
                    $result = db_query($qry);
                    while ($row = array_shift($result)) {
                        $bgColor_val = "#e4e4e4";
                        if ($row["date"] < $todays_dt) {
                            $bgColor_val = "red";
                        }

                        $b2bid = 0;
                        $sql = "SELECT b2bid FROM loop_warehouse where id = '" . $row["wid"] . "'";
                        db();
                        $result_comp = db_query($sql);
                        while ($row_comp = array_shift($result_comp)) {
                            $b2bid = $row_comp["b2bid"];
                        }

                    ?>
                        <tr vAlign="center">
                            <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">
                                <?php echo $row["trid"]; ?></td>
                            <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">
                                <?php echo $row["type"]; ?></td>

                            <?php
                            if ($row['type'] == "Inbound") { ?>
                                <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">
                                    <u>
                                        <a href="viewCompany.php?ID=<?php echo $b2bid ?>&show=transactions&warehouse_id=<?php echo $row["wid"] ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $row["wid"] ?>&rec_id=<?php echo $row["trid"] ?>&display=buyer_ship"><?php echo get_nickname_val($row["warehousename"], $b2bid); ?></a>
                                    </u>
                                </td>
                            <?php } else { ?>
                                <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">
                                    <p align="center">
                                        <span class="infotxt_freight "><u><a href="http://loops.usedcardboardboxes.com/search_results.php?warehouse_id=<?php echo $row["wid"] ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $row["wid"] ?>&rec_id=<?php echo $row["trid"]; ?>&display=buyer_ship"><?php echo getnickname($row["warehousename"], $row["wid"]); ?></a></u>
                                            <span style="width:570px;">
                                                <table cellSpacing="1" cellPadding="1" border="0" width="570">
                                                    <tr align="middle">
                                                        <td class="style7" colspan="3" style="height: 16px"><strong>SALE ORDER
                                                                DETAILS FOR ORDER ID: <?php echo $row["trid"] ?></strong></td>
                                                    </tr>

                                                    <tr vAlign="center">
                                                        <td bgColor="#e4e4e4" width="70" class="style17">
                                                            <font size=1>
                                                                <strong>QTY</strong>
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
                                                    $get_sales_order = db_query("Select *, loop_salesorders.notes AS A, loop_salesorders.pickup_date AS B, loop_salesorders.freight_vendor AS C, loop_salesorders.time AS D, loop_boxes.isbox AS I From loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = '" . $row["trid"] . "'");

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
                                                                    <font size="1" Face="arial"><?php echo $boxes["bdescription"]; ?>
                                                                    </font>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>

                                                    <?php
                                                    $soqry = "Select * From loop_salesorders_manual WHERE trans_rec_id = '" . $row["trid"] . "'";
                                                    db();
                                                    $get_sales_order2 = db_query($soqry);
                                                    while ($boxes2 = array_shift($get_sales_order2)) {
                                                    ?>
                                                        <tr bgColor="#e4e4e4">
                                                            <td height="13" class="style1" align="right">
                                                                <Font Face='arial' size='1'><?php echo $boxes2["qty"]; ?>
                                                            </td>
                                                            <td height="13" class="style1" align="right">&nbsp;</td>

                                                            <td align="left" height="13" style="width: 578px" class="style1" colspan=2>
                                                                <font size="1" Face="arial">
                                                                    &nbsp;&nbsp;<?php echo $boxes2["description"]; ?></font>
                                                            </td>
                                                        </tr>

                                                    <?php    }    ?>

                                                </table>
                                            </span>
                                        </span>
                                    </p>
                                </td>
                            <?php }

                            if ($row["date"] == "") { ?>
                                <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">&nbsp;</td>
                                <?php } else {
                                $fr_book_dt = strtotime($row["date"]);
                                $today_date = strtotime(date('m/d/Y'));
                                if ($today_date > $fr_book_dt) {
                                ?>
                                    <td bgColor="red" class="style3" align="center"><?php echo $row["date"]; ?></td>
                                <?php
                                } else if ($fr_book_dt == $today_date) {
                                ?>
                                    <td bgColor="yellow" class="style3" align="center"><?php echo $row["date"]; ?></td>
                                <?php
                                } else {
                                ?>
                                    <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">
                                        <?php echo $row["date"]; ?></td>
                            <?php
                                }
                            } ?>

                            <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">
                                <?php echo $row["time"]; ?></td>

            </td>

            <?php if ($row["picklist_print"] == "Y") { ?>
                <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">
                    <a href="#" onClick="PrintPickListRep('<?php echo $row["trid"] ?>' , '<?php echo $row["picklist_print"]; ?>')">Re-Print
                        Picklist</a>
                </td>
            <?php }
                        if ($row["picklist_print"] == "N") { ?>
                <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">&nbsp;</td>
            <?php }
                        if ($row["picklist_print"] == "" || $row["picklist_print"] == "null") { ?>
                <td bgColor="<?php echo $bgColor_val; ?>" class="style3" align="center">
                    <a href="#" onClick="PrintPickListRep('<?php echo $row["trid"] ?>' , '<?php echo $row["picklist_print"]; ?>')">Print
                        Picklist</a>
                </td>
            <?php } ?>



        </tr>
    <?php

                    }
                    db();
                    db_query("DELETE FROM huntvalley_temp");
    ?>
    </table>
    </td>

    <td valign="top" colSpan="2">
        <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

            <tr align="middle">
                <td class="style7" colSpan="7">
                    <b>Pending Order Pick-Ups</b>
                    <a href="javascript:void(0);" onclick="expand();">Expand/</a>
                    <a href="javascript:void(0);" onclick="collapse();">Collapse</a>
                </td>
            </tr>
            <tr>
                <td colSpan="7">
                    <div id="pending_ship"></div>
                </td>
            </tr>
        </table>
    </td>

    </tr>
    </table>
    <br><br>
    <!--Special Orders table-->
    <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

        <tr align="middle">
            <td class="style7" colSpan="8">
                <b>Special Orders</b>
            </td>
        </tr>

        <tr>
            <td class="style17" align="center">
                <b>Loop ID</b>
            </td>

            <td class="style17" align="center">
                <b>Company Name</b>
            </td>
            <td class="style17" align="center">
                <b>Deadline</b>
            </td>
            <td class="style17" align="center">
                <b>Print Picklist</b>
            </td>
            <td class="style17" align="center">
                <b>Last Note</b>
            </td>
            <td class="style17" align="center">
                <b>Last Note Date</b>
            </td>
            <td class="style17" align="center">
                <b>Update</b>
            </td>
            <td class="style17" align="center">
                <b>Processing Complete</b>
            </td>
        </tr>
        <?php
        $emp_id = $_COOKIE['employeeid'];
        $query = "select *, loop_transaction_buyer.id As wid from loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_salesorders ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id where special_order_complete = 0 and loop_transaction_buyer.shipped = 0 AND loop_transaction_buyer.needreprocessing =1 and loop_salesorders.location_warehouse_id = 18 AND loop_transaction_buyer.id > 67 group by loop_transaction_buyer.id order by reprocessingdeadline ASC";

        db();
        $res = db_query($query);
        while ($rows = array_shift($res)) {
            //echo $rows["warehouse_id"];
            // echo $rows["warehouse_name"]."<br>";
            $special_order_complete = $rows["special_order_complete"];
            $deadline_date = strtotime($rows["reprocessingdeadline"]);
            $rec_type = $rows["rec_type"];
            $todaydate = strtotime(date('m/d/Y'));
            if ($todaydate > $deadline_date) {
                $bgclass = "special_order_red";
            } elseif ($special_order_complete == 1) {
                $bgclass = "special_order_green";
            } else {
                $bgclass = "special_order_normal";
            }
            //echo $rows["wid"];
            $sql_translog = "SELECT message, date FROM loop_transaction_notes WHERE loop_transaction_notes.company_id = '" . $rows["warehouse_id"] . "' and loop_transaction_notes.rec_id = '" . $rows["wid"] . "' ORDER BY id DESC LIMIT 0,1";
            db();
            $result_translog = db_query($sql_translog);
            $last_note_text = "";
            $last_note_date = "";
            while ($last_translog = array_shift($result_translog)) {
                $lastnote_text = $last_translog["message"];
                $last_note_date = $last_translog["date"];
            }
        ?>
            <tr class="<?php echo $bgclass; ?>" id="so<?php echo $rows["wid"] ?>">
                <td class="style3">
                    <a href="viewCompany.php?ID=<?php echo $rows["b2bid"] ?>&show=transactions&warehouse_id=<?php echo $rows["warehouse_id"] ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $rows["warehouse_id"] ?>&rec_id=<?php echo $rows["wid"] ?>&display=buyer_view" target="_blank"><?php echo $rows["wid"]; ?></a>
                </td>
                <td class="style3">

                    <span class="so_infotxt">
                        <a href="viewCompany.php?ID=<?php echo $rows["b2bid"] ?>&show=transactions&warehouse_id=<?php echo $rows["warehouse_id"] ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $rows["warehouse_id"] ?>&rec_id=<?php echo $rows["wid"] ?>&display=buyer_view" target="_blank"> <?php echo $rows["warehouse_name"]; ?></a>
                        <!--Display sales order popup on hover-->
                        <span style="width:570px;">
                            <table cellSpacing="1" cellPadding="1" border="0" width="570">
                                <tr align="middle">
                                    <td class="style7" colspan="3" style="height: 16px"><strong>SALE ORDER DETAILS FOR ORDER
                                            ID: <?php echo $row["wid"] ?></strong></td>
                                </tr>
                                <tr vAlign="center">
                                    <td bgColor="#e4e4e4" width="70" class="style17">
                                        <font size=1>
                                            <strong>QTY</strong>
                                        </font>
                                    </td>
                                    <td bgColor="#e4e4e4" width="100" class="style17">
                                        <font size=1>
                                            <strong>Box Description</strong>
                                        </font>
                                    </td>
                                    <td bgColor="#e4e4e4" width="400" class="style17">
                                        <font size=1>
                                            <strong>Notes</strong>
                                        </font>
                                    </td>
                                </tr>
                                <?php
                                $get_sales_order = db_query("Select *, loop_salesorders.notes AS A, loop_salesorders.pickup_date AS B, loop_salesorders.freight_vendor AS C, loop_salesorders.time AS D, loop_boxes.isbox AS I From loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = '" . $rows["wid"] . "'");
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

                                        <td align="left" height="13" style="width: 578px" class="style1">
                                            <?php if ($boxes["I"] == "Y") { ?>
                                                <font size="1" Face="arial"><?php echo $boxes["blength"]; ?>
                                                    <?php echo $boxes["blength_frac"]; ?> x <?php echo $boxes["bwidth"]; ?>
                                                    <?php echo $boxes["bwidth_frac"]; ?> x <?php echo $boxes["bdepth"]; ?>
                                                    <?php echo $boxes["bdepth_frac"]; ?> <?php echo $boxes["bdescription"]; ?>
                                                </font>
                                            <?php } else { ?>
                                                <font size="1" Face="arial"><?php echo $boxes["bdescription"]; ?></font>
                                            <?php } ?>
                                        </td>
                                        <td height="13" style="width: 94px" class="style1" align="left">
                                            <Font Face='arial' size='1'>
                                                <?php
                                                echo $so_notes;
                                                ?>
                                        </td>
                                    </tr>

                                <?php } //End while $boxes = array_shift($get_sales_order)) 
                                ?>

                                <?php
                                $soqry = "Select * From loop_salesorders_manual WHERE trans_rec_id = '" . $rows["wid"] . "'";
                                db();
                                $get_sales_order2 = db_query($soqry);
                                while ($boxes2 = array_shift($get_sales_order2)) {
                                ?>
                                    <tr bgColor="#e4e4e4">
                                        <td height="13" class="style1" align="right">
                                            <Font Face='arial' size='1'><?php echo $boxes2["qty"]; ?>
                                        </td>
                                        <td height="13" class="style1" align="right">&nbsp;</td>
                                        <td align="left" height="13" style="width: 578px" class="style1" colspan=2>
                                            <font size="1" Face="arial">&nbsp;&nbsp;<?php echo $boxes2["description"]; ?></font>
                                        </td>
                                    </tr>
                                <?php
                                } //end while ($boxes2 = array_shift($get_sales_order2))	
                                ?>
                            </table>
                        </span>
                        <!--End display sales order popup on hover-->
                    </span>
                </td>
                <td class="style3">

                    <?php
                    if ($rows["reprocessingdeadline"] == "NULL" || $rows["reprocessingdeadline"] == "") {
                        $deadlinedate = "";
                    } else {
                        $deadlinedate = date('m/d/Y', strtotime($rows["reprocessingdeadline"]));
                    }
                    echo $deadlinedate; ?>
                    <input type="hidden" name="deadlinedate" id="deadlinedate<?php echo $rows["wid"]; ?>" value="<?php echo $deadlinedate; ?>">
                </td>
                <?php
                $bgColor_val = "#e4e4e4";
                if ($rows["picklist_print"] == "Y") {
                    $pprint = "Re-Print Picklist";
                ?>

                    <td class="style3" align="center">
                        <a href="#" onClick="PrintPickListRep_so('<?php echo $rows["wid"]; ?>' , '<?php echo $rows["picklist_print"]; ?>')">Re-Print
                            Picklist</a>
                    </td>
                <?php }
                if ($rows["picklist_print"] == "N") {
                    $pprint = "";
                ?>
                    <td class="style3" align="center">&nbsp;</td>
                <?php }
                if ($rows["picklist_print"] == "" || $rows["picklist_print"] == "null") {
                    $pprint = "Print Picklist";
                ?>
                    <td class="style3" align="center">
                        <a href="#" onClick="PrintPickListRep_so('<?php echo $rows["wid"]; ?>' , '<?php echo $rows["picklist_print"]; ?>')">Print
                            Picklist</a>
                    </td>
                <?php }
                ?>
                <input type="hidden" name="pprintdata" id="pprintdata<?php echo $rows["wid"] ?>" value="<?php echo isset($pprint); ?>">
                <td class="style3">
                    <textarea name="last_note_so" id="last_note_so<?php echo $rows["wid"]; ?>"><?php echo isset($lastnote_text); ?></textarea>

                </td>
                <td class="style3">
                    <div id="transdate_div<?php echo $rows["wid"] ?>">
                        <?php echo  $last_note_date; ?>
                    </div>
                </td>
                <td class="style3" align="center">

                    <input type="hidden" name="rect_type_so" id="rect_type_so<?php echo $rows["wid"] ?>" value="<?php echo $rec_type; ?>">
                    <input type="button" name="update_so" id='update_so<?php echo $rows["wid"] ?>' onclick="update_so_note(<?php echo $rows["wid"] ?>,<?php echo $rows["warehouse_id"]; ?>,<?php echo $emp_id; ?>); return false;" value="Update">
                    <div id="transinfo<?php echo $rows["wid"] ?>"></div>
                </td>
                <td class="style3" align="center">
                    <div id="complete_so_div<?php echo $rows["wid"] ?>">
                        <?php
                        if ($special_order_complete == 0) {
                        ?>

                            <input type="button" name="complete_so" id='complete_so<?php echo $rows["wid"] ?>' onclick="complete_specialorder_row(<?php echo $rows["wid"] ?>,<?php echo $rows["warehouse_id"]; ?>,1); return false;" value="Complete">

                        <?php
                        }
                        if ($special_order_complete == 1) {
                        ?>

                            Completed<br>
                            <input type="button" name="undo_complete_so" id='undo_complete_so<?php echo $rows["wid"] ?>' onclick="complete_specialorder_row(<?php echo $rows["wid"] ?>,<?php echo $rows["warehouse_id"]; ?>,0); return false;" value="Undo">

                        <?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <!--End Special Orders table-->
    <br /><br />
    <table>
        <tr>
            <!--<td valign="top">
			<table cellSpacing="1" cellPadding="1" border="0" width="700px;">
				 
				<?php
                $sql_pc = "SELECT * FROM `orders_active_ucb_hunt_valley` WHERE ship_status LIKE 'N' ";
                db();
                $result_pc = db_query($sql_pc);
                $count_pc2 = tep_db_num_rows($result_pc);
                ?>
					<tr align="middle">
					  <td colspan="2"class="style7" style="height: 16px"><strong>Pending Kit Stickers (Not Printed Yet)</strong></td>
					</tr>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">Hunt Valley</td>
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<?php echo $count_pc2; ?></td>
					</tr>
			</table>
		</td>-->
            <td valign="top" colSpan="3">
                <div id="hide_tr">
                    <table cellSpacing="1" cellPadding="1" border="0" width="700px">
                        <tr align="middle">
                            <td class="style7" colSpan="6" valign="top">
                                <b>INVENTORY</b>
                                <a href="javascript:void(0);" onclick="expand_inv();">Expand/</a>
                                <a href="javascript:void(0);" onclick="collapse_inv();">Collapse</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="inv"></div>
            </td>
        </tr>
    </table>
    <br><br>
    <!--B2C Inventory table-->
    <table>
        <tr>
            <td valign="top" colSpan="2">
                <div id="hide_b2cinv">
                    <table cellSpacing="1" cellPadding="1" border="0" width="700px">
                        <tr align="middle">
                            <td class="style7" colSpan="6" valign="top">
                                <b>B2C Inventory</b>
                                <a href="javascript:void(0);" onclick="expand_b2cinv();">Expand/</a>
                                <a href="javascript:void(0);" onclick="collapse_b2cinv();">Collapse</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="b2c_inv"></div>
            </td>
        </tr>
    </table>
    <br><br>

    <!------------- McCormick Trailer Report ---------------->

    <?php


    if ($_REQUEST["action"] == 'run') {

        $start_date = date('Ymd', $start_date);
        $end_date = date('Ymd', $end_date + 86400);

        if ($start_date > $end_date) {
            echo "<font size=4 color=red>Error: End Date before Start Date</font>";
        }

    ?>

        <table width=1400>
            <tr>
                <td>

                    <input type=hidden name="surveyview" value="<?php echo $_REQUEST["surveyview"]; ?>">
                    <input type=hidden name="start_date" value="<?php echo $_REQUEST["start_date"]; ?>">
                    <input type=hidden name="end_date" value="<?php echo $_REQUEST["end_date"]; ?>">
                    <input type=hidden name="action" value="run">
                    <table cellSpacing="1" cellPadding="1" width="550" border="0">

                        <tr align="middle">
                            <td class="style7">
                                <b>McCORMICK TRAILER REPORT</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 150" class="style17" align="center">
                                <b>DATE REQUEST</b></font>
                            </td>
                            <td style="width: 100" class="style17" align="center">
                                <b>TRANS #</b>
                            </td>
                            <td style="width: 100" class="style17" align="center">
                                <b>TRAILER #</b></font>
                            </td>
                            <td style="width: 50" class="style17" align="center">
                                <b>DOCK</b></font>
                            </td>
                            <td class="style5" style="width: 150" align="center">
                                <b>REQUESTED BY</b>
                            </td>
                            <td align="middle" style="width: 100" class="style16" align="center">
                                <b>VALUE</b>
                            </td>
                            <td align="middle" style="width: 100" class="style16" align="center">
                                <b>STATUS</b>
                            </td>
                        </tr>


                        <?php
                        $query = "SELECT * FROM loop_transaction WHERE loop_transaction.ignore = 0 and warehouse_id = 15 AND";
                        if ($_REQUEST["start_date"] != "") {
                            $query .= " pr_requestdate BETWEEN '" . $_REQUEST["start_date"] . "'";
                        }
                        if ($_REQUEST["end_date"] != "") {
                            $query .= " AND '" . $_REQUEST["end_date"] . "' ORDER BY pr_requestdate DESC";
                        }

                        $grandtotal = 0;
                        db();
                        $res = db_query($query);
                        while ($row = array_shift($res)) {

                        ?>
                            <tr vAlign="center">
                                <td bgColor="#e4e4e4" class="style3" align="center">
                                    <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
                                <td bgColor="#e4e4e4" class="style3" align="center">
                                    <a href="http://loops.usedcardboardboxes.com/mccormickdashboard_76345679315467990452.php?action=run&start_date=<?php echo htmlspecialchars($_REQUEST["start_date"]); ?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&surveyview=<?php echo $_REQUEST["surveyview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1" ?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&surveyview=<?php echo $_REQUEST["surveyview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1"?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&surveyview=<?php echo $_REQUEST["surveyview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1"?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&surveyview=<?php echo $_REQUEST["surveyview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1"><?php echo $row["dt_trailer"]; ?></a>
                                </td>
                                <td bgColor="#e4e4e4" class="style3" align="center">
                                    <?php echo $row["pr_dock"]; ?></td>
                                <td bgColor="#e4e4e4" class="style3">
                                    <?php echo $row["pr_requestby"]; ?></td>
                                <td bgColor="#e4e4e4" class="style3" align="right">

                                    <?php
                                    $gbw = 0;
                                    $vob = 0;

                                    $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_boxes_sort.trans_rec_id = '" . $row["id"] . "'";
                                    db();
                                    $dt_view_res = db_query($dt_view_qry);

                                    while ($dt_view_row = array_shift($dt_view_res)) {
                                        if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
                                            $gbw += $dt_view_row["bweight"] * $dt_view_row["boxgood"];
                                            $vob += $dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"];
                                        }
                                    }

                                    $voo = 0;

                                    $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = '" . $row["id"] . "' AND loop_boxes.isbox LIKE 'N'";
                                    db();
                                    $dt_view_res = db_query($dt_view_qry);

                                    while ($dt_view_row = array_shift($dt_view_res)) {
                                        if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
                                            $voo += $dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"];
                                        }
                                    }
                                    ?>
                                    $<?php
                                        $grandtotal += number_format($vob + $voo + $row["othercharge"] + $row["freightcharge"], 2);
                                        echo number_format($vob + $voo + $row["othercharge"] + $row["freightcharge"], 2); ?>



                                </td>
                                <td bgColor="#e4e4e4" class="style3" align="center">
                                    <?php
                                    if ($row["pmt_entered"] != 0) {
                                        echo "Paid";
                                    } elseif ($row["sort_entered"] == 1) {
                                        echo "Sorted";
                                    } elseif ($row["pa_employee"] != "") {
                                        echo "In Process";
                                    } else {
                                        echo "Requested";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td bgColor="#e4e4e4" class="style3" colspan="4" align="right">
                                <b>TOTAL</b></font>
                            </td>
                            <td bgColor="#e4e4e4" class="style3" align="right">
                                <b><?php echo $grandtotal; ?></b></font>
                            </td>
                            <td bgColor="#e4e4e4" class="style3">
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign="top">
                <?php
            }

            if ($_REQUEST["trailer"] > 0) {
                $dt_view_qry = "SELECT * FROM loop_transaction WHERE loop_transaction.ignore = 0 and id = '" . $_REQUEST["trailer"] . "'";
                db();
                $dt_view_res = db_query($dt_view_qry);

                $dt_view_trl_row = array_shift($dt_view_res)
                ?>
                    <table cellSpacing="1" cellPadding="1" border="0" width="800">
                        <tr align="middle">
                            <td class="style7" colspan="10" style="height: 16px"><strong>SORT REPORT FOR TRAILER
                                    #<?php echo $dt_view_trl_row["pr_trailer"]; ?></strong></td>
                        </tr>
                        <tr align="middle">
                            <td bgColor="88EEEE" colspan="10" class="style17"><strong>BOXES</strong></td>
                        </tr>
                        <tr vAlign="center">
                            <td bgColor="#e4e4e4" class="style12">Good Boxes</td>
                            <td bgColor="#e4e4e4" class="style12">Bad Boxes</td>
                            <td bgColor="#e4e4e4" width="350" class="style12">Description</td>
                            <td bgColor="#e4e4e4" class="style12">Box Weight</td>
                            <td bgColor="#e4e4e4" class="style12">Value Per Box</td>
                            <td bgColor="#e4e4e4" class="style12">Value of Boxes</td>
                        </tr>
                        <?php
                        $gb = 0;
                        $bb = 0;
                        $gbw = 0;
                        $vob = 0;


                        $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_boxes_sort.trans_rec_id = '" . $_REQUEST["trailer"] . "'";
                        db();
                        $dt_view_res = db_query($dt_view_qry);

                        while ($dt_view_row = array_shift($dt_view_res)) {

                            if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
                        ?>
                                <tr>
                                    <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["boxgood"]; ?></td>
                                    <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["boxbad"]; ?></td>
                                    <td bgColor="#e4e4e4" class="style12left">
                                        <?php echo $dt_view_row["blength"]; ?> <?php echo $dt_view_row["blength_frac"]; ?> x
                                        <?php echo $dt_view_row["bwidth"]; ?> <?php echo $dt_view_row["bwidth_frac"]; ?> x
                                        <?php echo $dt_view_row["bdepth"]; ?> <?php echo $dt_view_row["bdepth_frac"]; ?>
                                        <?php echo $dt_view_row["bdescription"]; ?></td>
                                    <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["bweight"]; ?></td>
                                    <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["sort_boxgoodvalue"]; ?></td>
                                    <td bgColor="#e4e4e4" class="style12right">
                                        <?php echo number_format($dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"], 2); ?>
                                    </td>
                                </tr>


                        <?php
                                $gb += $dt_view_row["boxgood"];
                                $bb += $dt_view_row["boxbad"];
                                $gbw += $dt_view_row["bweight"] * $dt_view_row["boxgood"];
                                $vob += $dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"];
                            }
                        } ?>

                        <tr>
                            <td bgColor="#e4e4e4" class="style12right"><strong><?php echo $gb; ?></strong></td>
                            <td bgColor="#e4e4e4" class="style12right"><strong><?php echo $bb; ?></strong></td>
                            <td bgColor="#e4e4e4" class="style12"><strong>BOX TOTALS</strong></td>
                            <td bgColor="#e4e4e4" class="style12right">
                                <strong><?php echo number_format($gbw, 2); ?></strong>
                            </td>
                            <td bgColor="#e4e4e4" class="style12"> </td>
                            <td bgColor="#e4e4e4" class="style12right">
                                <strong>$<?php echo number_format($vob, 2); ?></strong>
                            </td>
                        </tr>

                        <tr align="middle">
                            <td bgColor="88EEEE" colspan="10" class="style17"><strong>OTHER ITEMS</strong></td>
                        </tr>
                        <tr vAlign="center">
                            <td bgColor="#e4e4e4" colspan="2" class="style12">Quantity</td>
                            <td bgColor="#e4e4e4" class="style12left">Description</td>
                            <td bgColor="#e4e4e4" class="style12right">Units</td>
                            <td bgColor="#e4e4e4" class="style12right">Value Per Unit</td>
                            <td bgColor="#e4e4e4" class="style12right">Total Value</td>
                        </tr>
                        <?php


                        $voo = 0;


                        $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = '" . $_REQUEST["trailer"] . "' AND loop_boxes.isbox LIKE 'N'";
                        db();
                        $dt_view_res = db_query($dt_view_qry);

                        while ($dt_view_row = array_shift($dt_view_res)) {

                            if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
                        ?>
                                <tr>
                                    <td bgColor="#e4e4e4" colspan="2" class="style12right"><?php echo $dt_view_row["boxgood"]; ?>
                                    </td>
                                    <td bgColor="#e4e4e4" class="style12left">
                                        <?php echo $dt_view_row["bdescription"]; ?></td>
                                    <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["bunit"]; ?></td>
                                    <td bgColor="#e4e4e4" class="style12right">
                                        <?php echo number_format($dt_view_row["sort_boxgoodvalue"], 3); ?></td>
                                    <td bgColor="#e4e4e4" class="style12right">
                                        <?php echo number_format($dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"], 2); ?>
                                    </td>
                                </tr>


                        <?php
                                $voo += $dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"];
                            }
                        } ?>

                        <tr>

                            <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>OTHER ITEM TOTALS</strong></td>

                            <td bgColor="#e4e4e4" class="style12right">
                                <strong>$<?php echo number_format($voo, 2); ?></strong>
                            </td>
                        </tr>
                        <tr align="middle">
                            <td bgColor="88EEEE" colspan="10" class="style17"><strong>TOTALS</strong></td>
                        </tr>
                        <tr>
                            <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>GROSS EARNINGS</strong></td>
                            <td bgColor="#e4e4e4" class="style12right">
                                <strong>$<?php echo number_format($vob + $voo, 2); ?></strong>
                            </td>
                        </tr>
                        <?php if ($dt_view_trl_row["othercharge"] != 0) { ?>
                            <tr>
                                <td bgColor="#e4e4e4" colspan="5" class="style12right">
                                    <strong><?php echo $dt_view_trl_row["otherdetails"]; ?></strong>
                                </td>
                                <td bgColor="#e4e4e4" class="style12right">
                                    <strong>$<?php echo number_format($dt_view_trl_row["othercharge"], 2); ?></strong>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>FREIGHT</strong></td>
                            <td bgColor="#e4e4e4" class="style12right">
                                <strong>$<?php echo number_format($dt_view_trl_row["freightcharge"], 2); ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>TOTAL EARNED</strong></td>
                            <td bgColor="#e4e4e4" class="style12right">
                                <strong>$<?php echo number_format($vob + $voo + $dt_view_trl_row["othercharge"] + $dt_view_trl_row["freightcharge"], 2); ?></strong>
                            </td>
                        </tr>




                    <?php } ?>


                </td>
            </tr>
        </table>

        <div id="light" class="white_content">
        </div>
        <div id="fade" class="black_overlay"></div>


        <div id="diveod" style="display:none;">
            <form action="<?php echo huntvalleywarehousepage_test(); ?>" name="frmeod" id="frmeod" method="post">
                <table>
                    <tr>
                        <td><strong>Confirm Qty of Kits Shipped</strong></td>
                    </tr>
                    <tr>
                        <td>Enter No. of Qty of Kits Shipped Today: &nbsp;<input type="text" name="qtyofkitship" id="qtyofkitship" value="" /></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="btnqtyofkitship" id="btnqtyofkitship" value="Update" />
                            <input type="hidden" name="hd_warehouse" id="hd_warehouse" value="" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <?php
        $query = "Update tblvariable set variablevalue = '" . $get_all_red_row_cnt . "' where variablename = 'HV_red_row_cnt'";
        db();
        $res = db_query($query);

        if ($HV_red_row_loop_ids_str != "") {
            $HV_red_row_loop_ids_str = substr($HV_red_row_loop_ids_str, 0, strlen($HV_red_row_loop_ids_str) - 1);
            $query = "Update tblvariable set variablevalue = '" . $HV_red_row_loop_ids_str . "' where variablename = 'HV_red_row_loop_ids'";
            db();
            $res = db_query($query);
        }
        ?>
</body>

</html>