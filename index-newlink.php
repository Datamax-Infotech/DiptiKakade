<?php
//session_start();
//if(!session_is_registered(myusername)){
if (!$_COOKIE['userloggedin']) {
    header("location:login.php");
}

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();

if (isset($_REQUEST["trans_rec_id"])) {
    if ($_REQUEST["trans_rec_id"] > 0) {
        $qry = "UPDATE loop_transaction_buyer SET loop_transaction_buyer.ignore = 1 WHERE id = " . $_REQUEST["trans_rec_id"];
        $dt_view_res = db_query($qry);
        $dt_view_row = array_shift($dt_view_res);
    }
}

if (isset($_REQUEST["trans_rec_seller_id"])) {
    if ($_REQUEST["trans_rec_seller_id"] > 0) {
        $qry = "UPDATE loop_transaction SET loop_transaction.ignore = 1 WHERE id = " . $_REQUEST["trans_rec_seller_id"];
        $dt_view_res = db_query($qry);
        $dt_view_row = array_shift($dt_view_res);
    }
}

function totestglobal(): void
{
    global $cntnew;

    $cntnew = $cntnew + 1;

    //echo "Funtionm Cntnew" . $cntnew . "<br>";
}

$cntnew = 0;
$arrlength = 100;
for ($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {
    totestglobal();

    //	echo "Loop Cntnew" . $cntnew . "<br>";
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title>UCB Loop System - Home <?php echo "Logged in as : " . $_COOKIE['userinitials']; ?></title>
    <meta http-equiv="refresh" content="3000">

    <script type="text/javascript">
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

    function displaytrans_log(cnt, warehouse_id, rec_id) {
        document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        document.getElementById('light').style.display = 'block';

        selectobject = document.getElementById("translog" + cnt);
        n_left = f_getPosition(selectobject, 'Left');
        n_top = f_getPosition(selectobject, 'Top');

        document.getElementById('light').style.left = (n_left + 10) + 'px';
        document.getElementById('light').style.top = n_top - 50 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>" +
                    xmlhttp.responseText;

            }
        }

        xmlhttp.open("GET", "displaytrans_log.php?warehouse_id=" + warehouse_id + "&rec_id=" + rec_id, true);
        xmlhttp.send();
    }

    function reminder_popup_set5(compid, rec_id, warehouse_id, rec_type, cnt) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("reminder_popup_set5_btn" + cnt);
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';
                document.getElementById('light_reminder').style.top = n_top - 40 + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "sendemail_b2bsurvey.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
            warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }

    function confirmationIgnore(a, b) {
        var answer = confirm("Ignore " + a + "?");
        if (answer) {
            window.location = "index.php?trans_rec_id=" + b;
        } else {
            alert("Request Cancelled");
        }
    }

    function confirmationIgnore_seller(a, b) {
        var answer = confirm("Ignore " + a + "?");
        if (answer) {
            window.location = "index.php?trans_rec_seller_id=" + b;
        } else {
            alert("Request Cancelled");
        }
    }

    function showtable(tablenm, sort_order_pre, sort) {

        var emp_list_selected = document.getElementById("emp_list").value;
        //alert(emp_list_selected);
        document.getElementById("maindiv").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (tablenm == "customernotready" || tablenm == "all_03" || tablenm == "all_410" || tablenm ==
                "all_1014" || tablenm == "all_inbound" || tablenm == "blankopsdeliverydt") {
                // change the readyState from 3 to 4 as server loading setting changed
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("maindiv").innerHTML = xmlhttp.responseText;
                }
            } else {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("maindiv").innerHTML = xmlhttp.responseText;
                    document.getElementById("tablenm").value = tablenm;
                    document.getElementById("sort_order_pre").value = sort_order_pre;
                    document.getElementById("sort").value = sort;
                }
            }
        }

        xmlhttp.open("GET", "loop_index_load_tables.php?tablenm=" + tablenm + "&sort_order_pre=" + sort_order_pre +
            "&sort=" + sort + "&emp_list_selected=" + emp_list_selected, true);
        xmlhttp.send();
    }

    function showtablerefresh(tablenm, sort_order_pre, sort) {
        alert(tablenm + " " + sort_order_pre + " " + sort);

        document.getElementById("maindiv").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (tablenm == "customernotready" || tablenm == "all_03" || tablenm == "all_410" || tablenm ==
                "all_1014" || tablenm == "all_inbound") {
                // change the readyState from 3 to 4 as server loading setting changed
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("maindiv").innerHTML = xmlhttp.responseText;
                }
            } else {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("maindiv").innerHTML = xmlhttp.responseText;
                }
            }
        }

        xmlhttp.open("GET", "loop_index_load_tables.php?tablenm=" + tablenm + "&sort_order_pre=" + sort_order_pre +
            "&sort=" + sort, true);
        xmlhttp.send();
    }

    function showtable_12(tablenm, sort_order_pre, sort) {
        document.getElementById("maindiv").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            // change the readyState from 3 to 4 as server loading setting changed
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("maindiv").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_aging.php", true);
        xmlhttp.send();
    }

    function showtable_b2b_survey(tablenm, sort_order_pre, sort) {
        window.open('report_search_b2b_survey.php', '_blank');
    }

    function showtable_former_fulfillmentissue(tablenm, sort_order_pre, sort) {
        window.open('report_search_fulfillment_issue.php', '_blank');
    }

    function showtable_former_orderissue(tablenm, sort_order_pre, sort) {
        window.open('report_search_order_issue.php', '_blank');
    }

    function showtable_rep_fr(tablenm, sort_order_pre, sort) {
        window.open('report_freight_broker.php', '_blank');
    }

    function showtable_13(tablenm, sort_order_pre, sort, dobulechkflg) {
        //window.open("report_commissions_public_new.php?dobulechkflg="+ dobulechkflg +"&eid=allselected&unpaid_paid=Unpaid&date_from=&date_to=&reprun=yes&paidunpaid_flg=Unpaid", '_blank');
        window.open(
            "report_commissions.php?eid=allselected&match_confirmed=not_double_chk&date_from=&date_to=&boxtype=all&reprun=yes&paidunpaid_flg=Unpaid"
        );
    }

    function showtable_14(tablenm, sort_order_pre, sort, dobulechkflg) {
        window.open(
            "report_commissions.php?eid=allselected&match_confirmed=double_chk_complete&date_from=&date_to=&boxtype=all&reprun=yes&paidunpaid_flg=Unpaid"
        );
    }

    /*function showtable_13(tablenm, sort_order_pre, sort, dobulechkflg) 
    {
    	document.getElementById("maindiv").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 				

    	if (window.XMLHttpRequest)
    	{
    	  xmlhttp=new XMLHttpRequest();
    	}
    	else
    	{
    	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    	}
    	xmlhttp.onreadystatechange=function()
    	{
    	  // change the readyState from 3 to 4 as server loading setting changed
    	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    	  {
    		document.getElementById("maindiv").innerHTML = xmlhttp.responseText; 
    	  }
    	}
    	
    	xmlhttp.open("GET","report_commissions_public_new.php?dobulechkflg="+ dobulechkflg +"&eid=allselected&unpaid_paid=Unpaid&date_from=&date_to=&reprun=yes&paidunpaid_flg=Unpaid",true);			
    	xmlhttp.send();		
    }	*/

    function editdata(ctrid, rec_id) {
        document.getElementById("vendor_edit" + ctrid).innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("vendor_edit" + ctrid).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_commissions_public_new_edit.php?rec_id=" + rec_id + "&ctrid=" + ctrid, true);
        xmlhttp.send();
    }

    function adddata(rec_id) {
        document.getElementById("adddiv" + rec_id).innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("adddiv" + rec_id).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_commissions_public_new_add.php?rec_id=" + rec_id, true);
        xmlhttp.send();
    }

    function deletedata(ctrid, rec_id) {
        var alertval = confirm("Are you sure you want to delete the record.");
        if (alertval) {

        } else {
            return false;
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("vendor_edit" + ctrid).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_commissions_public_new_delete.php?rec_id=" + rec_id + "&ctrid=" + ctrid, true);
        xmlhttp.send();
    }


    function editdata_save() {
        var rec_id = document.getElementById("rec_id").value;
        var buyer_pay_id = document.getElementById("buyer_pay_id").value;
        var selstatus = document.getElementById("selstatus").value;
        var typeid = document.getElementById("typeid").value;
        var vendor_amt = document.getElementById("vendor_amt").value;
        var vendor_notes = document.getElementById("vendor_notes").value;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("vendor_edit" + buyer_pay_id).innerHTML = xmlhttp.responseText;
            }
        }
        var vendor_notes_new = escape(vendor_notes);

        xmlhttp.open("GET", "report_commissions_public_new_save.php?editsave=y&rec_id=" + rec_id + "&buyer_pay_id=" +
            buyer_pay_id + "&selstatus=" + selstatus + "&typeid=" + typeid + "&vendor_amt=" + vendor_amt +
            "&vendor_notes=" + vendor_notes_new, true);
        xmlhttp.send();
    }

    function adddata_save(rec_id) {
        var selstatus = document.getElementById("status").value;
        var typeid = document.getElementById("typeid").value;
        var vendor_amt = document.getElementById("amount").value;
        var vendor_notes = document.getElementById("notes").value;
        var vendor_comp = document.getElementById("company_id").value;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("vendor_add_rec" + rec_id).innerHTML = xmlhttp.responseText;
                document.getElementById("adddiv" + rec_id).innerHTML = "";
            }
        }
        var vendor_notes_new = escape(vendor_notes);

        xmlhttp.open("GET", "report_commissions_public_new_addsave.php?rec_id=" + rec_id + "&selstatus=" + selstatus +
            "&vendor_comp=" + vendor_comp + "&typeid=" + typeid + "&vendor_amt=" + vendor_amt + "&vendor_notes=" +
            vendor_notes_new, true);
        xmlhttp.send();
    }

    function show_freight_booking_vendor(cnt, broker_id) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("freight_booking_vendor"+cnt).innerHTML = xmlhttp.responseText; 

                selectobject = document.getElementById("freight_booking_vendor" + cnt);
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';
                document.getElementById('light_reminder').style.top = n_top - 40 + 'px';
                document.getElementById('light_reminder').style.width = 500 + 'px';

            }
        }

        xmlhttp.open("GET", "loop_index_showfreightvendor.php?cnt=" + cnt + "&broker_id=" + broker_id, true);
        xmlhttp.send();
    }

    function showtable_inbound(tablenm, sort_order_pre, sort) {
        document.getElementById("maindiv").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("maindiv").innerHTML = xmlhttp.responseText;
            }
        }

        var emp_list_selected = document.getElementById("emp_list").value;

        xmlhttp.open("GET", "loop_index_load_tables_inbound.php?tablenm=" + tablenm + "&sort_order_pre=" +
            sort_order_pre + "&sort=" + sort + "&emp_list_selected=" + emp_list_selected, true);
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
        var txtfreight_booked_delivery_date = '';
        if (document.getElementById('txtfreight_booked_delivery_date' + cnt)) {
            txtfreight_booked_delivery_date = document.getElementById('txtfreight_booked_delivery_date' + cnt).value;
        }
        var txtfr_dock_appointment = '';
        if (document.getElementById('txtfr_dock_appointment' + cnt)) {
            txtfr_dock_appointment = document.getElementById('txtfr_dock_appointment' + cnt).value;
        }

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
            "&txt_booked_delivery_cost=" + txt_booked_delivery_cost + "&txtfr_dock_appointment=" +
            txtfr_dock_appointment, true);
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

    function update_details(cnt) {
        var transid = document.getElementById('transid' + cnt).value;
        var note = document.getElementById('note' + cnt).value;
        var warehouseid = document.getElementById('warehouseid' + cnt).value;

        var tablenm = document.getElementById('tablenm' + cnt).value;

        var txtpo_delivery_dt = "";
        if (document.getElementById('txtpo_delivery_dt' + cnt)) {
            txtpo_delivery_dt = document.getElementById('txtpo_delivery_dt' + cnt).value;
        }
        var txtops_delivery_dt = "";
        if (document.getElementById('txtops_delivery_dt' + cnt)) {
            txtops_delivery_dt = document.getElementById('txtops_delivery_dt' + cnt).value;
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

        xmlhttp.open("GET", "loop_index_updatedata.php?cnt=" + cnt + "&updatedata=1&transid=" + transid +
            "&entinfo_link=" + entinfo_link + "&warehouseid=" + warehouseid + "&tablenm=" + tablenm + "&note=" +
            note + "&txtpo_delivery_dt=" + txtpo_delivery_dt + "&txtops_delivery_dt= " + txtops_delivery_dt, true);
        xmlhttp.send();
    }
    </script>

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
        left: 5%;
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

    .stylenew {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: left;
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

    .white_content {
        display: none;
        position: absolute;
        top: 10%;
        left: 10%;
        width: 35%;
        height: 25%;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
        box-shadow: 8px 8px 5px #888888;
    }
    </style>

    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
</head>

<body>
    <?php
    echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
    ?>

    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();

    var cal2_bxx = new CalendarPopup("listdiv");
    cal2_bxx.showNavigationDropdowns();
    </script>

    <div id="fade" class="black_overlay"></div>
    <div id="light_reminder" class="white_content_reminder"></div>
    <div id="light" class="white_content"></div>

    <input type="hidden" name="tablenm" id="tablenm" value="" />
    <input type="hidden" name="sort_order_pre" id="sort_order_pre" value="" />
    <input type="hidden" name="sort" id="sort" value="" />

    <?php include("inc/header.php"); ?>
    <div class="main_data_css">

        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                <?php if ($_REQUEST["linkfrm"] == "dash_cancelorders") { ?>
                Cancelled B2B Transactions Summary Report
                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">This report shows the user all cancelled B2B transactions
                        (ignored).</span>
                </div><br>
                <?php } ?>
                <?php if ($_REQUEST["linkfrm"] == "dash_blankopsdeliverydt") { ?>
                Needs Ops Delivery Date Updated Tool
                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">This tool shows the user all B2B transactions where the Ops Delivery Date
                        has passed, and allows the user to update it accordingly. Ideally, the table would be
                        blank.</span>
                </div><br>
                <?php } ?>
                <?php if ($_REQUEST["linkfrm"] == "dash_blankdockhours") { ?>
                Exception Report - Shipping/Receiving Dock Hours
                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">This report allows the user to see all company records which have the
                        Shipping/Receiving Dock Hours field blank. Ideally, these would all be filled in, and thus this
                        report would be blank. If it is not blank, we need to contact the customers to determine their
                        shipping and receiving dock hours, and then fill in the field with the answer. This helps the
                        freight department be effective and efficient.</span>
                </div><br>
                <?php } ?>

            </div>
        </div>
        <form method="POST" action="">
            <font size="1">Select Employee:</font>

            <?php
            db_b2b();
            $emp_initials = "";
            $chk_emptyrec = "";
            $qry_emp = "SELECT * FROM employees where status = 'Active' order by name";
            $qry_emp_res = db_query($qry_emp);
            echo "<select name='emp_list' id='emp_list' onchange='this.form.submit()'>";
            echo "<option value='all' selected>All</option>";
            while ($emp_row_rs = array_shift($qry_emp_res)) {
                $emp_initials = $emp_row_rs["initials"];
                $emp_name = $emp_row_rs["name"];

                echo "<option value='" . $emp_initials . "' ";
                if ($emp_initials == $_REQUEST["emp_list"]) {
                    echo " selected ";
                }
                echo " >" . $emp_name . "</option>";
            }
            echo "</select>";
            ?>
        </form>
        <br>
        <?php
        $sort_order_pre = "ASC";

        if (isset($_REQUEST['sort_order_pre'])) {
            if ($_REQUEST['sort_order_pre'] == "ASC") {
                $sort_order_pre = "DESC";
            } else {
                $sort_order_pre = "ASC";
            }
        }

        $sort = "";
        if (isset($_REQUEST['sort'])) {
            $sort = $_REQUEST['sort'];
        }
        ?>
        <table border="0" cellpadding="5" cellspacing="2" width="95%" align="center">

            <tr>
                <td valign="top" colspan="3">
                    <div id="maindiv"></div>


                    <!-- 		<div id="maindiv1"></div>
		<div id="maindiv2"></div>
		<div id="maindiv3"></div>
		<div id="maindiv4"></div>
		<div id="maindiv5"></div>
		<div id="maindiv6"></div>
		<div id="maindiv7"></div>
		<div id="maindiv8"></div>
		<div id="maindiv9"></div>
		<div id="maindiv10"></div>
		<div id="maindiv11"></div>
		<div id="maindiv12"></div>
		<div id="maindiv13"></div>
		<div id="maindiv14"></div>
		<div id="maindiv15"></div> -->
                </td>
            </tr>

        </table>

        <?php if ($_REQUEST["tablenm"] == "poentered") { ?>
        <script>
        showtable('poentered', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>

        <?php if ($_REQUEST["tablenm"] == "pouploaded") { ?>
        <script>
        showtable('pouploaded', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "customernotready") { ?>
        <script>
        showtable('customernotready', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>

        <?php if ($_REQUEST["tablenm"] == "customerready") { ?>
        <script>
        showtable('customerready', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "enterintoTMS") { ?>
        <script>
        showtable('enterintoTMS', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "tenderlane") { ?>
        <script>
        showtable('tenderlane', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "lanetendered") { ?>
        <script>
        showtable('lanetendered', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "bolcreated") { ?>
        <script>
        showtable('bolcreated', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "onroad") { ?>
        <script>
        showtable('onroad', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "delivered") { ?>
        <script>
        showtable('delivered', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "requestinvoice_10a10b") { ?>
        <script>
        showtable('requestinvoice_10a10b', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "qbinvoice") { ?>
        <script>
        showtable('qbinvoice', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "awaitingpayment") { ?>
        <script>
        showtable('awaitingpayment', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "doublechecksforpayroll") { ?>
        <script>
        showtable('doublechecksforpayroll', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>
        <?php if ($_REQUEST["tablenm"] == "compdoublechecksforpayroll") { ?>
        <script>
        showtable('compdoublechecksforpayroll', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>

        <?php if ($_REQUEST["tablenm"] == "blankdockhours") { ?>
        <script>
        showtable('blankdockhours', '<?php echo $sort_order_pre; ?>', '<?php echo $sort; ?>');
        </script>
        <?php     }    ?>

        <?php
        tep_db_close();
        ?>

    </div>


    <script type="text/javascript">
    var url = window.location.href;
    var parm = url.split("&");
    var newval = parm[1];
    //var searchstring= newval.split("=");
    if (newval == "linkfrm=dash") {
        //chkObject("maindiv")
        //createGrid();
        //checkContainer ()
        showtable('orderissue', 'ASC', '');
    }
    if (newval == "linkfrm=dash_act_fulfill") {
        //chkObject("maindiv")
        //createGrid();
        //checkContainer ()
        showtable('fulfillmentissue', 'ASC', '');
    }
    //
    if (newval == "linkfrm=dash_cancelorders") {
        //chkObject("maindiv")
        //createGrid();
        //checkContainer ()
        showtable('cancelorders', 'ASC', '');
    }
    //
    if (newval == "linkfrm=dash_blankopsdeliverydt") {
        //chkObject("maindiv")
        //createGrid();
        //checkContainer ()
        showtable('blankopsdeliverydt', 'ASC', '');
    }
    //
    if (newval == "linkfrm=dash_blankdockhours") {
        //chkObject("maindiv")
        //createGrid();
        //checkContainer ()
        showtable('blankdockhours', 'ASC', '');
    }
    </script>
</body>

</html>