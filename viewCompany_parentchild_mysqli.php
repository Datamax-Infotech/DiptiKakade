<?php


?>
<style>
.parent_tr {
    border-collapse: collapse;
}

.parent_tr td,
.parent_tr th {
    border: 1px solid #FFF;
    padding: 3px;
}
</style>
<script>
function displaysorteddata(colid, sortflg) {
    document.getElementById("div_sortdata").innerHTML =
        "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("div_sortdata").innerHTML = xmlhttp.responseText;
        }
    }

    compid = document.getElementById("maincompid").value;

    xmlhttp.open("GET", "parent_child_sortdata.php?ID=" + compid + "&colid=" + colid + "&sortflg=" + sortflg, true);
    xmlhttp.send();
}

function load_div_parent_child(id) {
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

    document.getElementById('light').style.left = '100px';
    document.getElementById('light').style.top = elementTop + 100 + 'px';
}

function load_div_parent_child_p(id) {
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

    document.getElementById('light').style.left = '100px';
    document.getElementById('light').style.top = elementTop + 100 + 'px';
}

function close_div() {
    document.getElementById('light').style.display = 'none';
}
</script>

<link rel="stylesheet" href="sorter/style_serchpg.css" />
<?php

$grand_summtd_SUMPO = "";
$grand_tot_trans = "";
$parent_child_flg = "";
$parent_child_compid = 0;
$loopid_org = 0;
$parent_child_compid_org = 0;
$on_hold = 0;
$salescomp = "yes";
$tot_rev_txt = "Total Revenue";
$water_opp = "";
$sql = "SELECT parent_child,parent_comp_id, loopid, haveNeed, on_hold, ucbzw_flg FROM companyInfo Where ID =" . $_REQUEST["ID"];
db_b2b();
$result_tmp = db_query($sql);
while ($myrowsel_tmp = array_shift($result_tmp)) {
    if ($myrowsel_tmp["ucbzw_flg"] == 1) {
        $water_opp = "yes";
    }
    $loopid_org = $myrowsel_tmp["loopid"];
    $on_hold = $myrowsel_tmp["on_hold"];
    $parent_child_flg = $myrowsel_tmp["parent_child"];
    $parent_child_compid = $myrowsel_tmp["parent_comp_id"];
    $parent_child_compid_org = $myrowsel_tmp["parent_comp_id"];
    if ($myrowsel_tmp["haveNeed"] == "Need Boxes") {
        $salescomp = "yes";
        $tot_rev_txt = "Total Revenue";
    } else {
        $salescomp = "no";
        $tot_rev_txt = "Total Payments from UCB";
    }
}

if ($parent_child_compid == 0) {
    $parent_child_compid = $_REQUEST["ID"];
}
//if ($parent_child_flg == "Child") {
?>
<input type="hidden" name="maincompid" id="maincompid" value="<?php echo $_REQUEST[" ID"]; ?>" />
<?php
//New cod
if ($parent_child_compid_org > 0) {
    $sql_child = "SELECT id FROM companyInfo where companyInfo.parent_child = 'Child' and active = 1 and status <> 31 and parent_comp_id = " . $parent_child_compid;
} else {
    $sql_child = "SELECT id FROM companyInfo where companyInfo.ID = '" . $_REQUEST["ID"] . "'";
}

db_b2b();
$res1w = db_query($sql_child);
//echo $sql_child;
?>
<div class='style24' style="width:1250; text-align:center;"><strong>
        <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Parent Company and Sibling Companies (
            <?php echo tep_db_num_rows($res1w); ?> customers)
        </font>
    </strong></div>
<div id="div_sortdata">
    <table width="1250" cellpadding="0" cellspacing="0">
        <thead>
            <tr class="parent_tr">
                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        Assigned To&nbsp;<a href="javascript:void();" onclick="displaysorteddata(13,1);"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(13,2);"><img src="images/sort_desc.jpg"
                                width="5px;" height="10px;"></a></font>
                </th>
                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">CONTACT&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(3,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(3,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>
                <th bgcolor="#D9F2FF">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">COMPANY NAME&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(4,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(4,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>
                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Status&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(9,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(9,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>
                <?php if ($salescomp == "no") { ?>
                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">UCBZeroWaste Account
                        Status&nbsp;<a href="javascript:void();" onclick="displaysorteddata(8,1);"><img
                                src="images/sort_asc.jpg" width="5px" height="10px"></a>&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(8,2);"><img src="images/sort_desc.jpg"
                                width="5px" height="10px"></font>
                </th>
                <?php } ?>

                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Next Step&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(10,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(10,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>
                <?php if ($salescomp == "yes") { ?>
                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Last<br>Delivery Date&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(16,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(16,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>
                <?php } else { ?>
                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Last<br>Pickup Date&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(16,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(16,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>
                <?php } ?>

                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Last<br>Communication&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(11,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(11,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>

                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Next Communication&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(12,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(12,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>

                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total sales transaction&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(14,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(14,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>

                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total sales revenue&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(15,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(15,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>

                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total Purchasing
                        transaction&nbsp;<a href="javascript:void();" onclick="displaysorteddata(17,1);"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(17,2);"><img src="images/sort_desc.jpg"
                                width="5px;" height="10px;"></a></font>
                </th>

                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total purchasing payments&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(18,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(18,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>


                <?php /*if ($salescomp == "yes") { ?>
                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total Revenue&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(15,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(15,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>
                <?php }else { ?>
                <th bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total Revenue From Sales&nbsp;<a
                            href="javascript:void();" onclick="displaysorteddata(15,1);"><img src="images/sort_asc.jpg"
                                width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                            onclick="displaysorteddata(15,2);"><img src="images/sort_desc.jpg" width="5px;"
                                height="10px;"></a></font>
                </th>
                <?php }*/ ?>
            </tr>
        </thead>
        <tbody>

            <?php
            //Code to display parent company records
            $parent_child_compid_loopid = 0;
            $total_locations_trans = 0;
            $tot_trans = 0;
            $tot_trans_p = 0;
            $total_summtd_p = 0;
            $total_summtd_s = 0;
            $total_s_trans = 0;
            $total_p_trans = 0;
            $summtd_SUMPO = 0;

            if ($parent_child_compid_org > 0) {
                $sql_b2b = "Select ucbzw_account_status, companyInfo.haveNeed, companyInfo.parent_child, companyInfo.last_date, companyInfo.status, companyInfo.on_hold ,companyInfo.ID AS I, companyInfo.shipCity,
					companyInfo.shipState, companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, 
					companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, 
					companyInfo.next_date AS ND, employees.initials AS EI, link_sales_id, link_purchasing_id from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID ";
                $sql_b2b = $sql_b2b . " where companyInfo.ID = " . $parent_child_compid . " and companyInfo.parent_child = 'Parent'";
            } else {
                $sql_b2b = "Select ucbzw_account_status, companyInfo.haveNeed, companyInfo.parent_child, companyInfo.last_date, companyInfo.status, companyInfo.on_hold ,companyInfo.ID AS I, companyInfo.shipCity,
					companyInfo.shipState, companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, 
					companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, 
					companyInfo.next_date AS ND, employees.initials AS EI, link_sales_id, link_purchasing_id from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID ";
                $sql_b2b = $sql_b2b . " where companyInfo.ID = " . $parent_child_compid . " ";
            }
            //echo "<br/>" . $sql_b2b . "<br/><br/>";

            db_b2b();
            $data_res_pc = db_query($sql_b2b);
            $bgcolor_val = "#E4E4E4";

            while ($data_rec_pc = array_shift($data_res_pc)) {

                if ($data_rec_pc["haveNeed"] == "Need Boxes") {
                    $salescomp_ind_com = "yes";
                } else {
                    $salescomp_ind_com = "no";
                }

                $parent_child_compid_loopid = $data_rec_pc["LID"];

                $status_val = "";
                $qry = "select * from status where id = '" . $data_rec_pc['status'] . "'";
                db_b2b();
                $dt_view_res = db_query($qry);
                while ($myrow = array_shift($dt_view_res)) {
                    $status_val = $myrow['name'];
                }

                $water_status_val = "";
                $qry = "select * from status where id = '" . $data_rec_pc['ucbzw_account_status'] . "'";
                db_b2b();
                $dt_view_res = db_query($qry);
                while ($myrow = array_shift($dt_view_res)) {
                    $water_status_val = $myrow['name'];
                }

                $on_hold_str = "";
                if ($data_rec_pc["on_hold"] == 1) {
                    $on_hold_str = " <font color=red>On Hold</font>";
                }

                $comp_to_display  = "";
                $comp_to_display1 = "";
                $comp_to_display2 = "";
                $bgcolor_val = "#ABC5DF";
                if ($data_rec_pc["parent_child"] == "Parent") {
                    $comp_to_display = "<";
                    $comp_to_display1 = ">";
                    $comp_to_display2 = ">: ";
                }
                if ($data_rec_pc["NN"] != "") {
                    $comp_nm = $data_rec_pc["NN"];
                } else {
                    $tmppos_1 = strpos($data_rec_pc["CO"], "-");
                    if ($tmppos_1 != false) {
                        $comp_nm = $data_rec_pc["CO"];
                    } else {
                        if ($data_rec_pc["shipCity"] <> "" || $data_rec_pc["shipState"] <> "") {
                            $comp_nm = $data_rec_pc["CO"] . " - " . $data_rec_pc["shipCity"] . ", " . $data_rec_pc["shipState"];
                        } else {
                            $comp_nm = $data_rec_pc["CO"];
                        }
                    }
                }

                $last_comm = $data_rec_pc["LD"];

                $last_delivery_date = "";
                $employee = "";
                if ($salescomp_ind_com == "yes") {
                    $qry = "SELECT bol_shipment_received_date from loop_bol_files inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_bol_files.trans_rec_id where loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.warehouse_id = " . $data_rec_pc['LID'] . " order by DATE_FORMAT(STR_TO_DATE(bol_shipment_received_date, '%m/%d/%Y'), '%Y-%m-%d') desc  limit 1";
                    db();
                    $dt_view_res = db_query($qry);
                    while ($myrow = array_shift($dt_view_res)) {
                        $last_delivery_date = $myrow['bol_shipment_received_date'];
                    }
                } else {
                    $qry = "SELECT pr_pickupdate from loop_transaction where loop_transaction.ignore = 0 and loop_transaction.warehouse_id = " . $data_rec_pc['LID'] . " order by DATE_FORMAT(STR_TO_DATE(pr_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') desc  limit 1";
                    db();
                    $dt_view_res = db_query($qry);
                    while ($myrow = array_shift($dt_view_res)) {
                        $last_delivery_date = $myrow['pr_pickupdate'];
                    }
                }
                $link_sid = 0;
                $link_pid = 0;
                if ($data_rec_pc['link_purchasing_id'] > 0) {
                    $lqry = "select ID, loopid from companyInfo where ID=" . $data_rec_pc['link_purchasing_id'];
                    db_b2b();
                    $l_res = db_query($lqry);
                    $lrows = array_shift($l_res);
                    $link_pid = $lrows["loopid"];
                }

                if ($data_rec_pc['link_sales_id'] > 0) {
                    $lqry = "select ID, loopid from companyInfo where ID=" . $data_rec_pc['link_sales_id'];
                    db_b2b();
                    $l_res = db_query($lqry);
                    $lrows = array_shift($l_res);
                    $link_sid = $lrows["loopid"];
                }

                if ($data_rec_pc['LID'] != 0) {
                    $qry = "select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = " . $data_rec_pc['LID'];
                    db();
                    $dt_view_res = db_query($qry);
                    while ($myrow = array_shift($dt_view_res)) {
                        $tot_trans = $tot_trans + $myrow['s_cnt'];
                    }
                    //
                    if ($data_rec_pc['link_purchasing_id'] > 0) {
                        $qry1 = "select count(id) as p_cnt from loop_transaction where `ignore` = 0 and warehouse_id = " . $data_rec_pc['link_purchasing_id'];
                        //echo $qry1;
                        db();
                        $dt_view_res1 = db_query($qry1);
                        while ($myrow1 = array_shift($dt_view_res1)) {
                            $tot_trans_p = $tot_trans_p + $myrow1['p_cnt'];
                        }
                    }

                    $qry1 = "select count(id) as p_cnt from loop_transaction where `ignore` = 0 and warehouse_id = " . $data_rec_pc['LID'];
                    db();
                    $dt_view_res1 = db_query($qry1);
                    while ($myrow1 = array_shift($dt_view_res1)) {
                        $tot_trans_p = $tot_trans_p + $myrow1['p_cnt'];
                    }
                    //
                    if ($data_rec_pc['link_sales_id'] > 0) {
                        $qry = "select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = " . $data_rec_pc['link_sales_id'];
                        db();
                        $dt_view_res = db_query($qry);
                        while ($myrow = array_shift($dt_view_res)) {
                            $tot_trans = $tot_trans + $myrow['s_cnt'];
                        }
                    }

                    if ($tot_trans > 0) {
                        $total_locations_trans = $total_locations_trans + 1;
                    }

                    if ($tot_trans_p > 0) {
                        $total_locations_trans_p = isset($total_locations_trans_p) + 1;
                    }
                }

                $lisoftrans = "<span style='width:600px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                $lisoftrans_p = "<span style='width:600px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";

                $lisoftrans .= "<tr><td bgColor='#ABC5DF'>Employee</td><td bgColor='#ABC5DF'>Closed Deal date</td><td bgColor='#ABC5DF'>Invoice Date</td><td bgColor='#ABC5DF'>LOOP ID</td><td bgColor='#ABC5DF'>Company Nickname</td><td bgColor='#ABC5DF'>Revenue Amount</td></tr>";

                $lisoftrans_p .= "<tr><td bgColor='#ABC5DF'>Employee</td><td bgColor='#ABC5DF'>Closed Deal date</td><td bgColor='#ABC5DF'>LOOP ID</td><td bgColor='#ABC5DF'>Company Nickname</td><td bgColor='#ABC5DF'>Revenue Amount</td></tr>";


                //kept this code at top as code takes time
                //if ($salescomp == "yes") {
                //}else{
                db_b2b();
                $result_finalpmt = db_query("Select employees.initials from companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where ID = " . $data_rec_pc["I"]);
                while ($summtd_finalpmt = array_shift($result_finalpmt)) {
                    $employee = $summtd_finalpmt["initials"];
                }
                //}	

                $tot_rev = 0;
                $summtd_SUMPO = 0;
                $tot_rev_p = 0;
                $summtd_SUMPO_p = 0;  //po_poorderamount
                if ($salescomp_ind_com == "yes") {
                    $qry_s = "SELECT loop_transaction_buyer.po_employee, transaction_date, total_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '" . $data_rec_pc['LID'] . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
                    //
                    db();
                    $dt_view_res = db_query($qry_s);
                    while ($myrow = array_shift($dt_view_res)) {
                        $inv_amt_totake = $myrow["total_revenue"];

                        $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;

                        $nickname = get_nickname_val($myrow["company_name"], $myrow["b2bid"]);

                        $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $employee . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'>" . $myrow["inv_date_of"] . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=buyer_payment'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                    }

                    if ($link_pid > 0) {
                        $qry_p = "SELECT transaction_date, estimated_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, loop_transaction.id, loop_transaction.warehouse_id FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.warehouse_id = '" . $link_pid . "' AND loop_transaction.ignore < 1 order by loop_transaction.id";

                        //
                        db();
                        $dt_view_res = db_query($qry_p);
                        while ($myrow = array_shift($dt_view_res)) {
                            $finalpaid_amt = 0;

                            $inv_amt_totake = $myrow["estimated_revenue"];

                            $summtd_SUMPO_p = $summtd_SUMPO_p + $inv_amt_totake;

                            $nickname = get_nickname_val($myrow["company_name"], $myrow["b2bid"]);
                            //
                            $lisoftrans_p .= "<tr><td bgColor='#E4EAEB'>" . $employee . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=seller_sort'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                        } //end while transaction query
                        //
                    }

                    //
                } else {
                    $qry_p = "SELECT transaction_date, estimated_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, loop_transaction.id, loop_transaction.warehouse_id FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.warehouse_id = '" . $data_rec_pc['LID'] . "' AND loop_transaction.ignore < 1 order by loop_transaction.id";
                    db();
                    $dt_view_res = db_query($qry_p);

                    while ($myrow = array_shift($dt_view_res)) {
                        $finalpaid_amt = 0;

                        $inv_amt_totake = $myrow["estimated_revenue"];

                        $summtd_SUMPO_p = $summtd_SUMPO_p + $inv_amt_totake;

                        $nickname = get_nickname_val($myrow["company_name"], $myrow["b2bid"]);
                        //


                        $lisoftrans_p .= "<tr><td bgColor='#E4EAEB'>" . $employee . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=seller_sort'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                    } //end while transaction query
                    //
                    if ($link_sid > 0) {
                        $qry_s = "SELECT loop_transaction_buyer.po_employee, total_revenue, transaction_date, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '" . $link_sid . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
                        //
                        db();
                        $dt_view_res = db_query($qry_s);
                        while ($myrow = array_shift($dt_view_res)) {
                            $inv_amt_totake = $myrow["total_revenue"];
                            $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;

                            $nickname = get_nickname_val($myrow["company_name"], $myrow["b2bid"]);



                            $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $employee . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'>" . $myrow["inv_date_of"] . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=buyer_payment'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                        }
                        //
                    }
                }


                if ($summtd_SUMPO > 0) {
                    $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
                }
                if ($summtd_SUMPO_p > 0) {
                    $lisoftrans_p .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO_p, 0) . "</td></tr>";
                }

                $lisoftrans .= "</table></span>";
                $lisoftrans_p .= "</table></span>";
                //
                $total_summtd_parent_s = $summtd_SUMPO;
                $total_summtd_parent_p = $summtd_SUMPO_p;
                $tot_parent_transs = isset($tot_parent_transs) + $tot_trans;
                $tot_parent_transp = isset($tot_parent_transp) + $tot_trans_p;
                //
            ?>
            <tr valign="middle" class="parent_tr">

                <td width="50px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $data_rec_pc["EI"] ?>
                    </font>
                </td>

                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data_rec_pc["LID"] > 0) echo "<b>"; ?><?php echo  $data_rec_pc["C"] ?>
                    </font>
                </td>
                <td width="200px" bgcolor="<?php echo $bgcolor_val; ?>"><a
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo  $data_rec_pc["I"] ?>"
                        target="_blank">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                            <?php echo $comp_nm . $on_hold_str; ?>
                        </font>
                    </a></td>

                <td width="150px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $status_val ?></font>
                </td>

                <?php if ($salescomp == "no") { ?>
                <td width="150px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $water_status_val ?>
                    </font>
                </td>
                <?php } ?>

                <td width="150px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data_rec_pc["LID"] > 0) echo "<b>"; ?><?php echo  $data_rec_pc["NS"] ?>
                    </font>
                </td>
                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo  $last_delivery_date ?></font>
                </td>

                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data_rec_pc["LID"] > 0) echo "<b>"; ?>
                        <?php if ($data_rec_pc["LD"] != "") echo date('m/d/Y', strtotime($last_comm)); ?>
                    </font>
                </td>
                <td width="100px" <?php if ($data_rec_pc["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00"
                    <?php } elseif ($data_rec_pc["ND"] < date('Y-m-d') && $data_rec_pc["ND"] != "") { ?>
                    bgcolor="#FF0000" <?php } else { ?> bgcolor="
                    <?php echo $bgcolor_val; ?>" <?php } ?> align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data_rec_pc["LID"] > 0) echo "<b>"; ?>
                        <?php if ($data_rec_pc["ND"] != "") echo date('m/d/Y', strtotime($data_rec_pc["ND"])); ?>
                    </font>
                </td>

                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $tot_trans ?></font>
                </td>

                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <?php if ($summtd_SUMPO > 0) { ?>
                    <a href='#' onclick='load_div_parent_child(<?php echo $data_rec_pc["I"]; ?>); return false;'>
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                            <?php echo number_format($summtd_SUMPO, 2) ?>
                        </font>
                    </a>
                    <span id='<?php echo $data_rec_pc["I"]; ?>' style='display:none;'><a href='#'
                            onclick='close_div(); return false;'>Close</a>
                        <?php echo $lisoftrans; ?>
                    </span>
                    <?php } ?>
                </td>
                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $tot_trans_p ?>
                    </font>
                </td>
                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <?php if ($summtd_SUMPO_p > 0) { ?>
                    <a href='#' onclick='load_div_parent_child_p(<?php echo $data_rec_pc["I"]; ?>); return false;'>
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                            <?php echo number_format($summtd_SUMPO_p, 2) ?>
                        </font>
                    </a>
                    <span id='<?php echo $data_rec_pc["I"]; ?>' style='display:none;'><a href='#'
                            onclick='close_div(); return false;'>Close</a>
                        <?php echo $lisoftrans_p; ?>
                    </span>
                    <?php } ?>
                </td>
            </tr>

            <?php        }

            //End display Parent company record
            ?>
            <?php

            $total_summtd_p = 0;
            $total_summtd_s = 0;
            $total_s_trans = 0;
            $total_p_trans = 0;

            $parent_child_compid_list = "0";
            $parent_child_compid_list_B2b = "0";
            $total_trans_p = "";
            $sql_child = "SELECT id FROM companyInfo where companyInfo.parent_child = 'Child' and active = 1 and status <> 31 and parent_comp_id = " . $parent_child_compid;
            db_b2b();
            $res1w = db_query($sql_child);
            //

            $link_id_str = "";
            if ($salescomp == "yes") {
                $link_id_str = " and link_purchasing_id <> ID";
            } else {
                $link_id_str = " and link_sales_id <> ID";
            }
            //Display new sibling records
            $sql_b2b = "Select ucbzw_account_status, companyInfo.haveNeed, companyInfo.parent_child, companyInfo.status, companyInfo.on_hold, companyInfo.ID AS I, companyInfo.shipCity, 
				companyInfo.shipState, companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname 
				AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, 
				companyInfo.next_date AS ND, employees.initials AS EI, link_sales_id, link_purchasing_id from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID ";
            $sql_b2b = $sql_b2b . " where companyInfo.parent_comp_id = " . $parent_child_compid . " and `active` = 1 and companyInfo.status <> 31 and companyInfo.parent_child = 'Child' 
				$link_id_str order by companyInfo.last_contact_date, companyInfo.state, companyInfo.dateCreated ";
            //and companyInfo.ID <> " . $b2bid . "
            //echo "<br/>" . $sql_b2b . "<br/><br/>";
            db_b2b();
            $data_res_pc = db_query($sql_b2b);
            $bgcolor_val = "#E4E4E4";
            $total_locations = 1;

            while ($data_rec_pc = array_shift($data_res_pc)) {
                if ($data_rec_pc["haveNeed"] == "Need Boxes") {
                    $salescomp_ind_com = "yes";
                } else {
                    $salescomp_ind_com = "no";
                }

                $summtd_SUMPO = 0;
                $total_locations = $total_locations + 1;

                $on_hold_str = "";
                if ($data_rec_pc["on_hold"] == 1) {
                    $on_hold_str = " <font color=red>On Hold</font>";
                }

                $comp_to_display  = "";
                $comp_to_display1 = "";
                $comp_to_display2 = "";
                $bgcolor_val = "#E4E4E4";
                if ($data_rec_pc["parent_child"] == "Parent") {
                    $comp_to_display = "<";
                    $comp_to_display1 = ">";
                    $comp_to_display2 = ">: ";
                    $bgcolor_val = "#F1EAB6";
                }
                if ($data_rec_pc["NN"] != "") {
                    $comp_nm = $data_rec_pc["NN"];
                } else {
                    $tmppos_1 = strpos($data_rec_pc["CO"], "-");
                    if ($tmppos_1 != false) {
                        $comp_nm = $data_rec_pc["CO"];
                    } else {
                        if ($data_rec_pc["shipCity"] <> "" || $data_rec_pc["shipState"] <> "") {
                            $comp_nm = $data_rec_pc["CO"] . " - " . $data_rec_pc["shipCity"] . ", " . $data_rec_pc["shipState"];
                        } else {
                            $comp_nm = $data_rec_pc["CO"];
                        }
                    }
                }

                $status_val = "";
                $qry_status = "select * from status where id = '" . $data_rec_pc['status'] . "'";
                db_b2b();
                $dt_view_res_status = db_query($qry_status);
                while ($myrow_status = array_shift($dt_view_res_status)) {
                    $status_val = $myrow_status['name'];
                }

                $water_status_val = "";
                $qry_status = "select * from status where id = '" . $data_rec_pc['ucbzw_account_status'] . "'";
                db_b2b();
                $dt_view_res_status = db_query($qry_status);
                while ($myrow_status = array_shift($dt_view_res_status)) {
                    $water_status_val = $myrow_status['name'];
                }

                if (trim($data_rec_pc['I']) != "" && $data_rec_pc['I'] > 0) {
                    $parent_child_compid_list_B2b = $parent_child_compid_list_B2b . $data_rec_pc['I'] . ",";
                }

                $tot_trans = "";
                $tot_revenue = 0;
                $last_delivery_date = "";
                if (trim($data_rec_pc['LID']) != "" && $data_rec_pc['LID'] > 0) {
                    $parent_child_compid_list = $parent_child_compid_list . $data_rec_pc['LID'] . ",";
                }
                //
                $link_pid = 0;
                $link_sid = 0;
                if ($salescomp_ind_com == "yes") {
                    //echo "sdfsdf".$data_rec_pc['link_purchasing_id'];
                    if ($data_rec_pc['link_purchasing_id'] > 0) {
                        $lqry = "select ID, loopid from companyInfo where ID=" . $data_rec_pc['link_purchasing_id'];
                        db_b2b();
                        $l_res = db_query($lqry);
                        $lrows = array_shift($l_res);
                        $link_pid = $lrows["loopid"];
                    }
                } else {
                    if ($data_rec_pc['link_sales_id'] > 0) {
                        $lqry = "select ID, loopid from companyInfo where ID=" . $data_rec_pc['link_sales_id'];
                        db_b2b();
                        $l_res = db_query($lqry);
                        $lrows = array_shift($l_res);
                        $link_sid = $lrows["loopid"];
                    }
                }
                //echo $data_rec_pc['link_purchasing_id'];
                //
                $tot_trans_p = "";
                $tot_trans = "";
                if ($data_rec_pc['LID'] > 0) {

                    if ($salescomp_ind_com == "yes") {
                        $qry1 = "select count(id) as cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = " . $data_rec_pc['LID'];

                        db();
                        $dt_view_res = db_query($qry1);
                        while ($myrow = array_shift($dt_view_res)) {
                            $tot_trans = $myrow['cnt'];
                        }
                        if ($tot_trans > 0) {
                            $total_locations_trans = $total_locations_trans + 1;
                        }
                        //
                        if ($data_rec_pc['link_purchasing_id'] > 0) {
                            $qry1 = "select count(id) as p_cnt from loop_transaction where `ignore` = 0 and warehouse_id = " . $data_rec_pc['link_purchasing_id'];
                            //echo $qry1;
                            db();
                            $dt_view_res1 = db_query($qry1);
                            while ($myrow1 = array_shift($dt_view_res1)) {
                                $tot_trans_p = $tot_trans_p + $myrow1['p_cnt'];
                            }
                        }
                        //

                        $qry = "SELECT bol_shipment_received_date from loop_bol_files inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_bol_files.trans_rec_id where loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.warehouse_id = " . $data_rec_pc['LID'] . " order by DATE_FORMAT(STR_TO_DATE(bol_shipment_received_date, '%m/%d/%Y'), '%Y-%m-%d') desc limit 1";
                        db();
                        $dt_view_res = db_query($qry);
                        while ($myrow = array_shift($dt_view_res)) {
                            $last_delivery_date = $myrow['bol_shipment_received_date'];
                        }
                    } else {
                        $qry1 = "select count(id) as p_cnt from loop_transaction where `ignore` = 0 and warehouse_id = " . $data_rec_pc['LID'];
                        db();
                        $dt_view_res = db_query($qry1);
                        while ($myrow = array_shift($dt_view_res)) {
                            $tot_trans_p = $myrow['p_cnt'];
                        }
                        if ($tot_trans_p > 0) {
                            $total_locations_trans_p = isset($total_locations_trans_p) + 1;
                        }
                        if ($data_rec_pc['link_sales_id'] > 0) {
                            $qry = "select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = " . $link_sid;
                            //echo $qry;
                            db();
                            $dt_view_res = db_query($qry);
                            while ($myrow = array_shift($dt_view_res)) {
                                $tot_trans = $tot_trans + $myrow['s_cnt'];
                            }
                            if ($tot_trans > 0) {
                                $total_locations_trans = $total_locations_trans + 1;
                            }
                        }

                        $qry = "SELECT pr_pickupdate from loop_transaction where loop_transaction.ignore = 0 and loop_transaction.warehouse_id = " . $data_rec_pc['LID'] . " order by DATE_FORMAT(STR_TO_DATE(pr_pickupdate, '%m/%d/%Y'), '%Y-%m-%d') desc  limit 1";
                        db();

                        $dt_view_res = db_query($qry);
                        while ($myrow = array_shift($dt_view_res)) {
                            $last_delivery_date = $myrow['pr_pickupdate'];
                        }
                    }
                }

                $lisoftrans = "<span style='width:600px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                $lisoftrans_p = "<span style='width:600px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";

                $lisoftrans .= "<tr><td bgColor='#ABC5DF'>Employee</td><td bgColor='#ABC5DF'>Closed Deal date</td><td bgColor='#ABC5DF'>Invoice Date</td><td bgColor='#ABC5DF'>LOOP ID</td><td bgColor='#ABC5DF'>Company Nickname</td><td bgColor='#ABC5DF'>Revenue Amount</td></tr>";

                $lisoftrans_p .= "<tr><td bgColor='#ABC5DF'>Employee</td><td bgColor='#ABC5DF'>Closed Deal date</td><td bgColor='#ABC5DF'>LOOP ID</td><td bgColor='#ABC5DF'>Company Nickname</td><td bgColor='#ABC5DF'>Revenue Amount</td></tr>";

                //kept this code at top as code takes time
                //if ($salescomp == "yes") {
                //}else{
                db_b2b();
                $result_finalpmt = db_query("Select employees.initials from companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where ID = " . $data_rec_pc["I"]);
                while ($summtd_finalpmt = array_shift($result_finalpmt)) {
                    $employee = $summtd_finalpmt["initials"];
                }
                //}	

                $tot_rev = 0;
                $summtd_SUMPO = 0;
                $tot_rev_p = 0;
                $summtd_SUMPO_p = 0;

                //po_poorderamount
                if ($salescomp_ind_com == "yes") {
                    $qry_s = "SELECT loop_transaction_buyer.po_employee, total_revenue, transaction_date, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '" . $data_rec_pc['LID'] . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
                    //
                    db();

                    $dt_view_res = db_query($qry_s);
                    while ($myrow = array_shift($dt_view_res)) {
                        $inv_amt_totake = $myrow["total_revenue"];

                        $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;

                        $nickname = get_nickname_val($myrow["company_name"], $myrow["b2bid"]);

                        if ($salescomp == "yes") {
                            $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . isset($employee) . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'>" . $myrow["inv_date_of"] . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=buyer_payment'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                        }
                    }
                    //
                    //
                    if ($link_pid > 0) {
                        $qry_p = "SELECT transaction_date, estimated_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, loop_transaction.id, loop_transaction.warehouse_id FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.warehouse_id = '" . $link_pid . "' AND loop_transaction.ignore < 1 order by loop_transaction.id";
                        //echo $qry_p . "<br>";
                        db();
                        $dt_view_res = db_query($qry_p);
                        while ($myrow = array_shift($dt_view_res)) {

                            $finalpaid_amt = 0;

                            //$inv_amt_totake= $myrow["estimated_revenue"];

                            $dt_view_qry = "SELECT SUM(boxgood*sort_boxgoodvalue) + SUM(boxbad*sort_boxbadvalue) AS totalInvAmt, loop_transaction.warehouse_id FROM loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id where loop_transaction.ignore = 0 and loop_transaction.id = '" . $myrow['id'] . "'";

                            //echo $totalInvAmt."<br>";
                            db();
                            $dt_view_res1 = db_query($dt_view_qry);
                            $num_rows = tep_db_num_rows($dt_view_res1);
                            if ($num_rows > 0) {
                                while ($dt_view_row = array_shift($dt_view_res1)) {
                                    //
                                    db();
                                    $resTotInvAmt = db_query("SELECT freightcharge, othercharge, warehouse_id FROM loop_transaction WHERE loop_transaction.ignore = 0  and loop_transaction.id = '" . $myrow['id'] . "'");
                                    while ($rowTotInvAmts = array_shift($resTotInvAmt)) {
                                        //
                                        $inv_amt_totake = $dt_view_row["totalInvAmt"] + $rowTotInvAmts['freightcharge'] + $rowTotInvAmts['othercharge'];
                                        $summtd_SUMPO_p = $summtd_SUMPO_p + $inv_amt_totake;
                                        $nickname = get_nickname_val($myrow["company_name"], $myrow["b2bid"]);

                                        //
                                        $lisoftrans_p .= "<tr><td bgColor='#E4EAEB'>" . isset($total_amt) . isset($employee) . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=seller_sort'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                                    }
                                }
                            }
                            //}

                        } //end while transaction query
                        //
                    }
                    //echo $qry_p."<br>";
                    //
                } else {
                    $summtd_SUMPO = 0;
                    $goodvalue = 0;
                    $badvalue = 0;
                    //AND loop_transaction.ignore < 1 
                    $qry_p = "SELECT transaction_date, estimated_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, loop_transaction.id, loop_transaction.warehouse_id, loop_transaction.freightcharge, loop_transaction.othercharge  FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.warehouse_id = '" . $data_rec_pc['LID'] . "' order by loop_transaction.id";
                    //echo $qry_p."<br>";
                    db();
                    $dt_view_res = db_query($qry_p);
                    while ($myrow = array_shift($dt_view_res)) {

                        $finalpaid_amt = 0;

                        //$inv_amt_totake= $myrow["estimated_revenue"];

                        $dt_view_qry = "SELECT SUM(boxgood*sort_boxgoodvalue) + SUM(boxbad*sort_boxbadvalue) AS totalInvAmt, loop_transaction.warehouse_id FROM loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id where loop_transaction.ignore = 0 and loop_transaction.id = '" . $myrow['id'] . "'";

                        //echo $totalInvAmt."<br>";
                        db();
                        $dt_view_res1 = db_query($dt_view_qry);
                        $num_rows = tep_db_num_rows($dt_view_res1);
                        if ($num_rows > 0) {
                            while ($dt_view_row = array_shift($dt_view_res1)) {
                                //
                                db();
                                $resTotInvAmt = db_query("SELECT freightcharge, othercharge, warehouse_id FROM loop_transaction WHERE loop_transaction.ignore = 0  and loop_transaction.id = '" . $myrow['id'] . "'");
                                while ($rowTotInvAmts = array_shift($resTotInvAmt)) {
                                    //
                                    $inv_amt_totake = $dt_view_row["totalInvAmt"] + $rowTotInvAmts['freightcharge'] + $rowTotInvAmts['othercharge'];

                                    $summtd_SUMPO_p = $summtd_SUMPO_p + $inv_amt_totake;

                                    $nickname = get_nickname_val($myrow["company_name"], $myrow["b2bid"]);

                                    //
                                    $lisoftrans_p .= "<tr><td bgColor='#E4EAEB'>" . isset($total_amt) . isset($employee) . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=seller_sort'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                                }
                            }
                        }
                        //}

                    } //end while transaction query


                    //
                    if ($link_sid > 0) {
                        $qry_s = "SELECT loop_transaction_buyer.po_employee, total_revenue, transaction_date, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '" . $link_sid . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
                        //echo $qry_s . "<br>";
                        //
                        db();
                        $dt_view_res = db_query($qry_s);
                        while ($myrow = array_shift($dt_view_res)) {
                            $inv_amt_totake = $myrow["total_revenue"];
                            $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;

                            $nickname = get_nickname_val($myrow["company_name"], $myrow["b2bid"]);

                            if ($salescomp == "yes") {
                                $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . isset($employee) . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'>" . $myrow["inv_date_of"] . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=buyer_payment'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                            }
                        }
                        //
                    }
                }

                if ($summtd_SUMPO > 0) {
                    $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
                }
                if ($summtd_SUMPO_p > 0) {
                    $lisoftrans_p .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO_p, 0) . "</td></tr>";
                }
                $lisoftrans .= "</table></span>";
                $lisoftrans_p .= "</table></span>";
                //
                $total_summtd_p = $total_summtd_p + $summtd_SUMPO_p;
                $total_summtd_s = $total_summtd_s + $summtd_SUMPO;
                $total_s_trans = $total_s_trans + $tot_trans;
                $total_p_trans = $total_p_trans + $tot_trans_p;
                //
            ?>
            <tr valign="middle" class="parent_tr">
                <td width="50px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $data_rec_pc["EI"] ?>
                    </font>
                </td>

                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data_rec_pc["LID"] > 0) echo "<b>"; ?><?php echo  $data_rec_pc["C"] ?>
                    </font>
                </td>
                <td width="200px" bgcolor="<?php echo $bgcolor_val; ?>"><a
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo  $data_rec_pc["I"] ?>"
                        target="_blank">
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                            <?php echo $comp_nm . $on_hold_str; ?>
                        </font>
                    </a></td>

                <td width="150px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $status_val ?></font>
                </td>
                <?php if ($salescomp == "no") { ?>
                <td width="150px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $water_status_val ?>
                    </font>
                </td>
                <?php } ?>

                <td width="150px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data_rec_pc["LID"] > 0) echo "<b>"; ?><?php echo  $data_rec_pc["NS"] ?>
                    </font>
                </td>

                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data_rec_pc["LID"] > 0) echo "<b>"; ?><?php echo  $last_delivery_date ?>
                    </font>
                </td>

                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data_rec_pc["LID"] > 0) echo "<b>"; ?>
                        <?php if ($data_rec_pc["LD"] != "") echo date('m/d/Y', strtotime($data_rec_pc["LD"])); ?>
                    </font>
                </td>
                <td width="100px" <?php if ($data_rec_pc["ND"] == date('Y-m-d')) { ?> bgcolor="#00FF00"
                    <?php } elseif ($data_rec_pc["ND"] < date('Y-m-d') && $data_rec_pc["ND"] != "") { ?>
                    bgcolor="#FF0000" <?php } else { ?> bgcolor="
                    <?php echo $bgcolor_val; ?>" <?php } ?> align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($data_rec_pc["LID"] > 0) echo "<b>"; ?>
                        <?php if ($data_rec_pc["ND"] != "") echo date('m/d/Y', strtotime($data_rec_pc["ND"])); ?>
                    </font>
                </td>

                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $tot_trans ?></font>
                </td>
                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <?php if ($summtd_SUMPO > 0) { ?>
                    <a href='#' onclick='load_div_parent_child(<?php echo $data_rec_pc["I"]; ?>); return false;'>
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                            <?php echo number_format($summtd_SUMPO, 2) ?>
                        </font>
                    </a>
                    <span id='<?php echo $data_rec_pc["I"]; ?>' style='display:none;'><a href='#'
                            onclick='close_div(); return false;'>Close</a>
                        <?php echo $lisoftrans; ?>
                    </span>
                    <?php } ?>
                </td>
                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo  $tot_trans_p ?>
                    </font>
                </td>
                <td width="100px" bgcolor="<?php echo $bgcolor_val; ?>" align="center">
                    <?php if ($summtd_SUMPO_p > 0) { ?>
                    <a href='#' onclick='load_div_parent_child_p(<?php echo $data_rec_pc["I"]; ?>); return false;'>
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                            <?php echo number_format($summtd_SUMPO_p, 2) ?>
                        </font>
                    </a>
                    <span id='<?php echo $data_rec_pc["I"]; ?>' style='display:none;'><a href='#'
                            onclick='close_div(); return false;'>Close</a>
                        <?php echo $lisoftrans_p; ?>
                    </span>
                    <?php } ?>
                </td>
            </tr>
            <?php

            }
            //
            $sales_revenue = isset($total_summtd_parent_s) + $total_summtd_s;
            $purchasing_pay = isset($total_summtd_parent_p) + $total_summtd_p;
            $sales_trans = isset($tot_parent_transs) + $total_s_trans;
            $purchase_trans = isset($tot_parent_transp) + $total_p_trans;
            //
            ?>
            <tr class="parent_tr" valign="middle">
                <?php if ($salescomp == "yes") { ?>
                <td width="100px" bgcolor="#D9F2FF" align="right" colspan="8">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><strong>Total</strong></font>
                </td>
                <?php } else { ?>
                <td width="100px" bgcolor="#D9F2FF" align="right" colspan="9">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><strong>Total</strong></font>
                </td>
                <?php } ?>

                <td width="100px" bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><strong>
                            <?php echo $sales_trans; ?>
                        </strong></font>
                </td>
                <td width="100px" bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><strong>$
                            <?php echo number_format($sales_revenue, 2); ?>
                        </strong></font>
                </td>
                <td width="100px" bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><strong>
                            <?php echo $purchase_trans; ?>
                        </strong></font>
                </td>
                <td width="100px" bgcolor="#D9F2FF" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><strong>$
                            <?php echo number_format($purchasing_pay, 2); ?>
                        </strong></font>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<br><br>
<?php //End new code 
?>
<?php

$want_to_bypass = "yes";

if ($want_to_bypass == "no") {
    $tot_trans = 0;
    if ($salescomp == "yes") {
        $qry = "Select count(id) as cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = " . $parent_child_compid;
    } else {
        $qry = "Select count(id) as cnt from loop_transaction where `ignore` = 0 and warehouse_id = " . $parent_child_compid;
    }

    db();
    $dt_view_res = db_query($qry);
    while ($myrow = array_shift($dt_view_res)) {
        $tot_trans = $myrow['cnt'];
    }
    if ($tot_trans > 0) {
        $total_locations_trans = $total_locations_trans + 1;
    }

?>
<br><br>
<div class='style24' style="width:330; text-align:center;"><strong>
        <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Locations Summary</font>
    </strong></div>
<table width="330" border="0" cellspacing="1" cellpadding="1" id="table_sibling1" class="sortable">
    <thead>
        <tr>
            <th width="50%" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total Locations Owned
            </th>
            <th width="50%" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total Locations UCB Has Sold To
                </font>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr valign="middle">
            <td width="50%" align="center" bgcolor="<?php echo $bgcolor_val; ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo $total_locations; ?>
                </font>
            </td>
            <td width="50%" align="center" bgcolor="<?php echo $bgcolor_val; ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo $total_locations_trans; ?>
                </font>
            </td>
        </tr>
    </tbody>
</table>

<br><br>
<div class='style24' style="width:500; text-align:center;"><strong>
        <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Account Management Summary</font>
    </strong></div>
<table width="500" border="0" cellspacing="1" cellpadding="1" id="table_sibling1" class="sortable">
    <thead>
        <tr>
            <th width="100px" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Employee Initials</font>
            </th>
            <th width="80px" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total Locations
            </th>
            <th width="150px" bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total Transactions Regardless of
                    Ownership</font>
            </th>
            <?php if ($salescomp == "yes") { ?>
            <th width="100px" bgcolor="#D9F2FF">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total Revenue</font>
            </th>
            <?php } else { ?>
            <th bgcolor="#D9F2FF" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Total Revenue From Sales&nbsp;<a
                        href="javascript:void();" onclick="displaysorteddata(15,1);"><img src="images/sort_asc.jpg"
                            width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();"
                        onclick="displaysorteddata(15,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;">
                </font>
            </th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php

            if ($parent_child_compid_list != "0") {
                $parent_child_compid_list = substr($parent_child_compid_list, 0, strlen($parent_child_compid_list) - 1);
                if ($parent_child_compid_loopid > 0) {
                    $parent_child_compid_list = $parent_child_compid_list . "," . $parent_child_compid_loopid;
                }
            }

            if ($parent_child_compid_list_B2b != "0") {
                $parent_child_compid_list_B2b = substr($parent_child_compid_list_B2b, 0, strlen($parent_child_compid_list_B2b) - 1);
                if ($parent_child_compid > 0) {
                    $parent_child_compid_list_B2b = $parent_child_compid_list_B2b . ", " . $parent_child_compid;
                }
            }


            $lisoftrans_final_tot = "<span style='width:600px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
            if ($salescomp == "yes") {
                $lisoftrans_final_tot .= "<tr><td bgColor='#ABC5DF'>Employee</td><td bgColor='#ABC5DF'>Closed Deal date</td><td bgColor='#ABC5DF'>Invoice Date</td><td bgColor='#ABC5DF'>LOOP ID</td><td bgColor='#ABC5DF'>Company Nickname</td><td bgColor='#ABC5DF'>Revenue Amount</td></tr>";
            } else {
                $lisoftrans_final_tot .= "<tr><td bgColor='#ABC5DF'>Employee</td><td bgColor='#ABC5DF'>Closed Deal date</td><td bgColor='#ABC5DF'>LOOP ID</td><td bgColor='#ABC5DF'>Company Nickname</td><td bgColor='#ABC5DF'>Revenue Amount</td></tr>";
            }

            $to_chk_flg = "no";
            if ($parent_child_compid_org > 0 || strtoupper($parent_child_flg) == strtoupper("Parent")) {
                if ($salescomp == "yes" && $parent_child_compid_list != "0") {
                    $sql_b2b = "Select po_employee, name, initials, loop_employees.id, loop_employees.b2b_id from loop_transaction_buyer left join loop_employees on loop_employees.initials = loop_transaction_buyer.po_employee where warehouse_id in (" . $parent_child_compid_list . ") and `ignore` = 0 group by po_employee";
                    db();
                    $data_res_pc = db_query($sql_b2b);
                    $to_chk_flg = "yes";
                } else {
                    if ($parent_child_compid_list_B2b != "0") {
                        $sql_b2b = "Select name, initials, employees.loopID as id, employees.employeeID as b2b_id from companyInfo left join employees on employees.employeeID = companyInfo.assignedto where ID in (" . $parent_child_compid_list_B2b . ") and active = 1 group by employees.employeeID";
                        db_b2b();
                        $data_res_pc = db_query($sql_b2b);
                        $to_chk_flg = "yes";
                    }
                }
            } else {
                $sql_b2b = "Select po_employee, name, initials, loop_employees.id, loop_employees.b2b_id from loop_transaction_buyer left join loop_employees on loop_employees.initials = loop_transaction_buyer.po_employee where warehouse_id = " . $loopid_org . " and `ignore` = 0 group by po_employee";
                db();
                $data_res_pc = db_query($sql_b2b);
                $to_chk_flg = "yes";
            }

            //echo $sql_b2b . "- " . $parent_child_compid_org . " - " . $salescomp . " - " . $parent_child_compid_list .  "<br>";

            if ($to_chk_flg == "yes") {
                $grand_summtd_SUMPO = 0;
                $bgcolor_val = "#E4E4E4";
                while ($data_rec_pc = array_shift($data_res_pc)) {

                    $tot_location = 0;
                    db_b2b();
                    $qry11 = "Select ID, loopid from companyInfo where assignedto = '" . $data_rec_pc['b2b_id'] . "' and ID in (" . $parent_child_compid_list_B2b . ")";
                    //echo $qry . "<br>";
                    $compid_list = "";
                    $dt_view_res11 = db_query($qry11);
                    while ($myrow11 = array_shift($dt_view_res11)) {
                        $tot_location = $tot_location + 1;
                        if ($myrow11['loopid'] > 0) {
                            $compid_list = $compid_list . $myrow11['loopid'] . ",";
                        }
                    }

                    db();

                    if ($compid_list != "") {
                        $compid_list = substr($compid_list, 0, strlen($compid_list) - 1);
                        if ($parent_child_compid_loopid > 0) {
                            $compid_list = $compid_list . "," . $parent_child_compid_loopid;
                        }
                    }

                    $tot_trans = 0;
                    if ($salescomp == "yes") {
                        if ($parent_child_compid_org > 0 || strtoupper($parent_child_flg) == strtoupper("Parent")) {
                            if ($parent_child_compid_list != "") {
                                $qry = "Select count(*) as cnt from loop_transaction_buyer where po_employee = '" . $data_rec_pc['po_employee'] . "' and `ignore` = 0 and loop_transaction_buyer.warehouse_id in (" . $parent_child_compid_list . ")";
                            }
                        } else {
                            $qry = "Select count(*) as cnt from loop_transaction_buyer where po_employee = '" . $data_rec_pc['po_employee'] . "' and `ignore` = 0 and loop_transaction_buyer.warehouse_id = " . $loopid_org . " ";
                        }
                    } else {
                        if ($compid_list != "") {
                            $qry = "Select count(*) as cnt from loop_transaction where loop_transaction.warehouse_id in (" . $compid_list . ") and `ignore` = 0";
                        }
                    }
                    //echo $qry . "<br>";
                    if ($qry != "") {

                        db();
                        $dt_view_res = db_query($qry);
                        while ($myrow = array_shift($dt_view_res)) {
                            $tot_trans = $myrow['cnt'];
                        }
                    }

                    $lisoftrans = "<span style='width:600px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                    if ($salescomp == "yes") {
                        $lisoftrans .= "<tr><td bgColor='#ABC5DF'>Employee</td><td bgColor='#ABC5DF'>Closed Deal date</td><td bgColor='#ABC5DF'>Invoice Date</td><td bgColor='#ABC5DF'>LOOP ID</td><td bgColor='#ABC5DF'>Company Nickname</td><td bgColor='#ABC5DF'>Revenue Amount</td></tr>";
                    } else {
                        $lisoftrans .= "<tr><td bgColor='#ABC5DF'>Employee</td><td bgColor='#ABC5DF'>Closed Deal date</td><td bgColor='#ABC5DF'>LOOP ID</td><td bgColor='#ABC5DF'>Company Nickname</td><td bgColor='#ABC5DF'>Revenue Amount</td></tr>";
                    }

                    $tot_rev = 0;
                    $summtd_SUMPO = 0; //po_poorderamount
                    if ($salescomp == "yes") {
                        if ($parent_child_compid_org > 0 || strtoupper($parent_child_flg) == strtoupper("Parent")) {
                            if ($parent_child_compid_list != "") {
                                $qry = "SELECT loop_transaction_buyer.po_employee, total_revenue, transaction_date, loop_warehouse.b2bid, loop_transaction_buyer.inv_amount as invsent_amt , loop_transaction_buyer.inv_date_of, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee = '" . $data_rec_pc['po_employee'] . "' AND loop_transaction_buyer.ignore < 1  and loop_transaction_buyer.warehouse_id in (" . $parent_child_compid_list . ") order by loop_transaction_buyer.id";
                            }
                        } else {
                            $qry = "SELECT loop_transaction_buyer.po_employee, total_revenue, transaction_date, loop_warehouse.b2bid, loop_transaction_buyer.inv_amount as invsent_amt , loop_transaction_buyer.inv_date_of, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee = '" . $data_rec_pc['po_employee'] . "' AND loop_transaction_buyer.ignore < 1  and loop_transaction_buyer.warehouse_id = " . $loopid_org . " order by loop_transaction_buyer.id";
                        }
                    } else {
                        if ($compid_list != "") {
                            $qry = "SELECT transaction_date, loop_warehouse.b2bid, loop_transaction.Total_revenue, loop_transaction.id, loop_transaction.warehouse_id FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.ignore < 1  and loop_transaction.warehouse_id in (" . $compid_list . ") order by loop_transaction.id";
                        }
                    }

                    //echo $qry . "<br>";
                    if ($qry != "") {

                        db();
                        $dt_view_res = db_query($qry);
                        while ($myrow = array_shift($dt_view_res)) {
                            $inv_amt_totake = $myrow["total_revenue"];

                            $employee = $myrow["po_employee"];

                            $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;

                            $nickname = get_nickname_val($myrow["company_name"], $myrow["b2bid"]);

                            if ($salescomp == "yes") {
                                $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $employee . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'>" . $myrow["inv_date_of"] . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=buyer_payment'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                                $lisoftrans_final_tot .= "<tr><td bgColor='#E4EAEB'>" . $employee . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'>" . $myrow["inv_date_of"] . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=buyer_payment'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                            } else {
                                $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $data_rec_pc['initials'] . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=buyer_payment'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                                $lisoftrans_final_tot .= "<tr><td bgColor='#E4EAEB'>" . $data_rec_pc['initials'] . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($myrow["transaction_date"])) . "</td><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $myrow["b2bid"] . "&show=transactions&warehouse_id=" . $myrow["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $myrow["warehouse_id"] . "&rec_id=" . $myrow["id"] . "&display=buyer_payment'>" . $myrow["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                            }
                        }
                    }

                    if ($summtd_SUMPO > 0) {
                        if ($salescomp == "yes") {
                            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
                        } else {
                            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
                        }
                    }
                    $lisoftrans .= "</table></span>";

            ?>
        <tr valign="middle">
            <td bgcolor="<?php echo $bgcolor_val; ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo $data_rec_pc["initials"]; ?>
                </font>
            </td>

            <td align="right" bgcolor="<?php echo $bgcolor_val; ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo $tot_location; ?>
                </font>
            </td>

            <td align="right" bgcolor="<?php echo $bgcolor_val; ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo number_format($tot_trans, 0); ?>
                </font>
            </td>

            <td align="right" bgcolor="<?php echo $bgcolor_val; ?>">
                <a href='#' onclick='load_div_parent_child(<?php echo $data_rec_pc["id"]; ?>); return false;'>
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                        <?php echo number_format($summtd_SUMPO, 2) ?>
                    </font>
                </a>
                <span id='<?php echo $data_rec_pc["id"]; ?>' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a>
                    <?php echo $lisoftrans; ?>
                </span>
            </td>

        </tr>

        <?php

                    $grand_tot_location = isset($grand_tot_location) + $tot_location;
                    $grand_tot_trans = isset($grand_tot_trans) + $tot_trans;
                    $grand_summtd_SUMPO = isset($grand_summtd_SUMPO) + $summtd_SUMPO;
                }
            }

            if (isset($grand_summtd_SUMPO) > 0) {
                $lisoftrans_final_tot .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($grand_summtd_SUMPO, 0) . "</td></tr>";
            }
            $lisoftrans_final_tot .= "</table></span>";

            ?>
        <tr valign="middle">
            <td bgcolor="<?php echo $bgcolor_val; ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><b>Total</b></font>
            </td>
            <td align="right" bgcolor="<?php echo $bgcolor_val; ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo isset($grand_tot_location); ?>
                </font>
            </td>
            <td align="right" bgcolor="<?php echo $bgcolor_val; ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo number_format($grand_tot_trans, 0); ?>
                </font>
            </td>
            <td align="right" bgcolor="<?php echo $bgcolor_val; ?>">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <a href='#' onclick='load_div_parent_child(989); return false;'>
                        <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><b>$
                                <?php echo number_format($grand_summtd_SUMPO, 2) ?>
                            </b></font>
                    </a>
                    <span id='989' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>
                        <?php echo $lisoftrans_final_tot; ?>
                    </span>
                </font>
            </td>
        </tr>
    </tbody>
</table>

<?php } ?>