<script language="javascript">
function deletetrans(trans_rec_id) {
    if (confirm("Do you want to delete the Transcation?") == true) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("rowid" + trans_rec_id).style.display = "none";
            }
        }

        xmlhttp.open("GET", "water_trans_delete.php?trans_rec_id=" + trans_rec_id, true);
        xmlhttp.send();
    }
}

function todoitem_delete_water(unqid, compid) {
    if (confirm("Do you want to delete the Initiative?") == true) {
        var pwdval = prompt("Please enter password to delete the Initiative", "");

        if (pwdval == "4652") {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById('light_todo').style.display = 'none';
                    document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "todolist_water_update.php?indelete_mode=1&unqid=" + unqid + "&compid=" + compid, true);
            xmlhttp.send();
        } else {
            alert("Password Incorrect!");
        }
    }
}

function todoitem_edit_water(unqid, compid) {
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            selectobject = document.getElementById("todo_edit_water" + unqid);
            n_left = f_getPosition(selectobject, 'Left');
            n_top = f_getPosition(selectobject, 'Top');

            document.getElementById("light_todo").innerHTML =
                "<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                xmlhttp.responseText;
            document.getElementById('light_todo').style.display = 'block';

            document.getElementById('light_todo').style.left = (n_left - 200) + 'px';
            document.getElementById('light_todo').style.top = (n_top - 50) + 'px';
            document.getElementById('light_todo').style.width = 900 + 'px';
            document.getElementById('light_todo').style.height = 200 + 'px';
        }
    }

    xmlhttp.open("GET", "todolist_water_edit_data.php?compid=" + compid + "&unqid=" + unqid, true);
    xmlhttp.send();
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

function update_edit_item_water(unqid) {
    document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('light_todo').style.display = 'none';
            document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
        }
    }

    var compid = document.getElementById('todo_companyID').value;
    var todo_message = document.getElementById('todo_message_edit').value;
    var task_detail = document.getElementById('todo_message_details_edit').value;
    var todo_employee = document.getElementById('todo_employee_edit').value;
    var todo_date = document.getElementById('todo_date_edit').value;

    xmlhttp.open("GET", "todolist_water_update.php?inedit_mode=1&unqid=" + unqid + "&compid=" + compid +
        "&todo_message=" + encodeURIComponent(todo_message) + "&task_detail=" + encodeURIComponent(task_detail) +
        "&todo_employee=" + todo_employee + "&todo_date=" + todo_date, true);
    xmlhttp.send();
}



function searchinvno(compid, rec_id) {
    txtinvno = document.getElementById("txtsrchinvno").value;

    if (txtinvno != "") {

        document.getElementById("water_maintbl").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("water_maintbl").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "water_get_inv_no.php?invno=" + txtinvno + "&comp_id=" + compid + "&rec_id=" + rec_id,
            true);
        xmlhttp.send();
    }
}

function fun_show_new_initiative() {
    var new_initiative_frm = document.getElementById('new_initiative_frm');
    var displaySetting = new_initiative_frm.style.display;
    // also get the  button, so we can change what it says
    var add_new_initiative = document.getElementById('add_new_initiative');

    // now toggle and the button text, depending on current state
    if (displaySetting == 'block') {
        // div is visible. hide it
        new_initiative_frm.style.display = 'none';
        // change button text
        // add_new_initiative.innerHTML = 'Show';
    } else {
        // div is hidden. show it
        new_initiative_frm.style.display = 'block';
        // change button text
        //add_new_initiative.innerHTML = 'Hide';
    }
}

function newinitiavite_cancel() {
    //cancel_newinitiavite_btn
    var new_initiative_frm = document.getElementById('new_initiative_frm');
    new_initiative_frm.style.display = 'none';
    //
    document.getElementById('task_title').value = "";
    document.getElementById('task_detail').value = "";
    document.getElementById('due_date').value = "<?php echo date('m/d/Y'); ?>";
    document.getElementById('task_owner').value = "";
}

function add_newinitiavite() {
    //alert(selectedText);
    document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
            //$( "#initiative_div" ).load(window.location.href + " #initiative_div" );
            document.getElementById('task_title').value = "";
            document.getElementById('task_detail').value = "";
            document.getElementById('task_owner').value = "";
            var new_initiative_frm = document.getElementById('new_initiative_frm');
            new_initiative_frm.style.display = 'none';
        }
    }

    var compid = document.getElementById('init_companyID').value;
    var task_title = document.getElementById('task_title').value;
    var task_detail = document.getElementById('task_detail').value;
    var due_date = document.getElementById('due_date').value;
    var task_owner = document.getElementById('task_owner').value;
    var init_created_by = document.getElementById('init_created_by').value;

    xmlhttp.open("GET", "water_initiavite_save.php?compid=" + compid + "&task_title=" + task_title + "&task_detail=" +
        encodeURIComponent(task_detail) + "&due_date=" + due_date + "&task_owner=" + task_owner +
        "&init_created_by=" + init_created_by, true);
    xmlhttp.send();
}

function init_markcomp(unqid, compid) {
    //alert(selectedText);
    document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
        }
    }

    xmlhttp.open("GET", "water_initiavite_save.php?compid=" + compid + "&taskid=" + unqid + "&markcomp=1", true);
    xmlhttp.send();
}
</script>


<?php

$tmp_bs_status = "";
$tmp_rec_type = "";
$id = "";
$rec_type = "";



if ($_REQUEST["show"] == "watertransactions") {
?>

<style type="text/css">
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
    left: 5%;
    width: 60%;
    height: 90%;
    padding: 16px;
    border: 1px solid gray;
    background-color: white;
    z-index: 1002;
    overflow: auto;
}
</style>

<div id="light_todo" class="white_content"></div>
<div id="fade_todo" class="black_overlay"></div>

<script LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></script>
<script LANGUAGE="JavaScript" SRC="inc/general.js"></script>
<script LANGUAGE="JavaScript">
document.write(getCalendarStyles());
</script>
<script LANGUAGE="JavaScript">
var cal2xxwater = new CalendarPopup("listdivwater");
cal2xxwater.showNavigationDropdowns();
</script>

<?php

    $loop_rec_found = "no";
    db_b2b();
    $res_totcnt = db_query("Select loopid from companyInfo where ID = " . $_REQUEST["ID"] . " and loopid > 0");
    while ($row_totcnt = array_shift($res_totcnt)) {
        $loop_rec_found = "yes";
    }

    if ($loop_rec_found == "no") {
        $sql = "SELECT * FROM companyInfo where ID = " . $_REQUEST["ID"] . " ";
        db_b2b();
        $result = db_query($sql);

        while ($myrowsel = array_shift($result)) {

            if ($myrowsel["haveNeed"] == "Need Boxes") {
                $tmp_rec_type = "Supplier";
                $tmp_bs_status = "Buyer";
            }

            if ($myrowsel["haveNeed"] == "Water") {
                $tmp_rec_type = "Water";
                $tmp_bs_status = "Water";
            }

            if ($myrowsel["haveNeed"] == "Have Boxes") {
                $tmp_rec_type = "Manufacturer";
                $tmp_bs_status = "Seller";
            }
            $tmp_company = preg_replace("/'/", "\'", $myrowsel["company"]);
            $tmp_address = preg_replace("/'/", "\'", $myrowsel["address"]);
            $tmp_address2 = preg_replace("/'/", "\'", $myrowsel["address2"]);
            $tmp_city = preg_replace("/'/", "\'", $myrowsel["city"]);
            $tmp_contact = preg_replace("/'/", "\'", $myrowsel["contact"]);

            $tmp_state = preg_replace("/'/", "\'", $myrowsel["state"]);
            $tmp_phone = preg_replace("/'/", "\'", $myrowsel["phone"]);
            $tmp_accounting_contact = preg_replace("/'/", "\'", $myrowsel["accounting_contact"]);
            $tmp_accounting_phone = preg_replace("/'/", "\'", $myrowsel["accounting_phone"]);

            //$tmp_company = preg_replace ( "/'/", "\'", $_REQUEST["company"]);
            //echo $tmp_company;

            $strQuery = "Insert into loop_warehouse (b2bid, company_name, company_address1, company_address2, company_city, company_state, company_zip, company_phone, company_email, company_contact, ";
            $strQuery = $strQuery . " warehouse_name, warehouse_address1, warehouse_address2, warehouse_city, warehouse_state, warehouse_zip, ";
            $strQuery = $strQuery . " warehouse_contact, warehouse_contact_phone, warehouse_contact_email, warehouse_manager, warehouse_manager_phone, warehouse_manager_email, ";
            $strQuery = $strQuery . " dock_details, warehouse_notes, ";
            $strQuery = $strQuery . " rec_type, bs_status, overall_revenue_comp, noof_location, accounting_email, accounting_contact, accounting_phone) ";
            $strQuery = $strQuery . " values(" . $_REQUEST["ID"]  . ", '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', ";
            $strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '" . $tmp_phone . "', ";
            $strQuery = $strQuery . " '" . $myrowsel["email"] . "', '" . $tmp_contact . "', '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', ";
            $strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '', '" . $tmp_phone . "', ";
            $strQuery = $strQuery . " '" . $myrowsel["email"] . "', '', '', '', '', '', ";
            $strQuery = $strQuery . " '" . $tmp_rec_type . "', '" . $tmp_bs_status . "', '" . $myrowsel["overall_revenue_comp"] . "', '" . $myrowsel["noof_location"] . "', '" . $myrowsel["accounting_email"] . "', '" . $tmp_accounting_contact . "', '" . $tmp_accounting_phone . "') ";

            db();
            $res = db_query($strQuery);
            //echo $strQuery;
            $new_loop_id = tep_db_insert_id();
            $id = $new_loop_id;
            db_b2b();
            db_query("Update companyInfo set loopid = " . $new_loop_id . " where ID = " . $_REQUEST["ID"]);

            $sql = "SELECT inventory.id as b2bid FROM boxes inner join inventory on inventory.id = boxes.inventoryid where boxes.inventoryid > 0 and boxes.companyid = " . $_REQUEST["ID"] . " ";
            db_b2b();
            $result_box = db_query($sql);

            while ($myrowsel_box = array_shift($result_box)) {

                db();
                $sql = "SELECT id FROM loop_boxes where b2b_id = " . $myrowsel_box["b2bid"] . " ";
                $result_box_loop = db_query($sql);

                while ($myrowsel_box_loop = array_shift($result_box_loop)) {
                    $sql = "Insert into loop_boxes_to_warehouse (loop_boxes_id, loop_warehouse_id ) SELECT " . $myrowsel_box_loop["id"] . ", " . $new_loop_id;
                    //echo $sql . "</br>";
                    db();
                    $result_box_loop_ins = db_query($sql);
                }
            }

            echo "<script type=\"text/javascript\">";
            echo "window.location.href=\"viewCompany-purchasing.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $new_loop_id .  "&rec_type=Manufacturer&show=watertransactions&proc=View&searchcrit=\";";
            echo "</script>";
            echo "<noscript>";
            echo "<meta http-equiv=\"refresh\" content=\"0;url=loop_sales_iframe_po_display.php?poadded=yes&ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $new_loop_id . "&rec_type=Manufacturer&show=watertransactions&proc=View&searchcrit\" />";
            echo "</noscript>";
            exit;
        }
    }

    echo "<Font Face='arial' size='4' color='#333333'><b>Transactions</b><br><br>";
    ?>

<form name="entry_form" id="entry_form" action="" method="post">
    <table cellSpacing="1" cellPadding="1" width="500" border="0">
        <tr>
            <td valign="top">

                <table cellSpacing="1" cellPadding="1" width="500" border="0" id="table15">
                    <tr align="middle">
                        <td bgColor="#c0cdda" colSpan="10">
                            <font size="1" color="#333333">WATER TRANSACTIONS</font>
                        </td>
                    </tr>
                    <?php

                        $sort_str_sel1 = "";
                        $sort_str_sel2 = "";
                        $sort_str_sel3 = "";
                        $sort_str_sel4 = "";
                        $sort_str_sel5 = "";
                        $sort_str_sel6 = "";
                        $sort_str = "order by transaction_date desc";
                        if (isset($_REQUEST['selsortby'])) {
                            if ($_REQUEST['selsortby'] == "invdate_new") {
                                $sort_str_sel1 = " selected ";
                                $sort_str = "order by invoice_date desc";
                            }
                            if ($_REQUEST['selsortby'] == "invdate_old") {
                                $sort_str_sel2 = " selected ";
                                $sort_str = "order by invoice_date asc";
                            }
                            if ($_REQUEST['selsortby'] == "vendor_asc") {
                                $sort_str_sel3 = " selected ";
                                $sort_str = "order by water_vendors.Name asc";
                            }
                            if ($_REQUEST['selsortby'] == "vendor_desc") {
                                $sort_str_sel4 = " selected ";
                                $sort_str = "order by water_vendors.Name desc";
                            }
                            if ($_REQUEST['selsortby'] == "totamt_low") {
                                $sort_str_sel5 = " selected ";
                                $sort_str = "order by amount asc";
                            }
                            if ($_REQUEST['selsortby'] == "totamt_high") {
                                $sort_str_sel6 = " selected ";
                                $sort_str = "order by amount desc";
                            }
                        }
                        ?>
                    <tr align="middle">
                        <td bgColor="#c0cdda" colSpan="10">
                            <font size="1">Sort By: <select name="selsortby" id="selsortby"
                                    onchange="this.form.submit()">
                                    <option value="invdate_new" <?php echo $sort_str_sel1; ?>>Service End Date
                                        (newest-oldest)</option>
                                    <option value="invdate_old" <?php echo $sort_str_sel2; ?>>Service End Date
                                        (oldest-newest)</option>
                                    <option value="vendor_asc" <?php echo $sort_str_sel3; ?>>Vendor Name (A-Z)</option>
                                    <option value="vendor_desc" <?php echo $sort_str_sel4; ?>>Vendor Name (Z-A)</option>
                                    <option value="totamt_low" <?php echo $sort_str_sel5; ?>>Total Amount (low-high)
                                    </option>
                                    <option value="totamt_high" <?php echo $sort_str_sel6; ?>>Total Amount (high-low)
                                    </option>
                                </select>
                                &nbsp;
                                Filter by Invoice Number: <input type="text" name="txtsrchinvno" id="txtsrchinvno"
                                    onkeypress="searchinvno(<?php echo $id; ?>, <?php echo $_REQUEST[" rec_id"]; ?>)" />
                            </font>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="10">
                            <div id="water_maintbl" style="height:300px; margin: 0; padding:0; overflow:scroll;">
                                <table cellSpacing="1" cellPadding="1" width="550" border="0">
                                    <tr bgColor="#e4e4e4">
                                        <td style="width: 105px; height: 13px" class="style1" align="center">
                                            DATE ENTERED
                                        </td>
                                        <td style="width: 105px; height: 13px" class="style1" align="center">
                                            LAST MODIFIED
                                        </td>
                                        <td style="width: 91px; height: 13px" class="style1" align="center">
                                            INVOICE NUMBER
                                        </td>
                                        <td style="width: 88px; height: 13px" class="style1" align="center">SERVICE END
                                            DATE
                                        </td>
                                        <td style="width: 87px; height: 13px" class="style1" align="center">VENDOR
                                        </td>
                                        <td style="height: 13px; width: 87px;" class="style1" align="center">VENDOR
                                            REPORT
                                        </td>
                                        <td style="height: 13px; width: 87px;" class="style1" align="center">TOTAL
                                            AMOUNT
                                        </td>
                                        <td style="height: 13px; width: 50px;" class="style1" align="center">FILES
                                        </td>
                                        <td style="height: 13px; width: 87px;" class="style1" align="center">DELETE
                                        </td>
                                    </tr>

                                    <?php

                                        if (isset($_REQUEST["rec_id"])) {
                                            if (isset($_REQUEST["show_all_trans"])) {
                                                $get_trans_sql = "SELECT * FROM water_transaction WHERE company_id = " . $id . " " . $sort_str . " LIMIT 0, 1000";
                                            } else {
                                                $sql_cnt = "SELECT (SELECT COUNT(*) FROM `water_transaction` WHERE company_id = " . $id . " and id >= " . $_REQUEST["rec_id"] . " order by id desc) AS `position`,";
                                                $sql_cnt .= "(SELECT COUNT(id) FROM `water_transaction` WHERE company_id = " . $id . " ) AS totcnt";
                                                $sql_cnt .= " FROM `water_transaction` ";
                                                $sql_cnt .= " WHERE company_id = " . $id . " and id =  " . $_REQUEST["rec_id"];
                                                //echo $sql_cnt . "<br>";
                                                db();
                                                $res_totcnt = db_query($sql_cnt);
                                                $show_all = "no";
                                                $rec_pos = 0;
                                                while ($row_totcnt = array_shift($res_totcnt)) {
                                                    if ($row_totcnt["position"] > 100) {
                                                        $show_all = "yes";
                                                        if ($row_totcnt["position"] > 0) {
                                                            $rec_pos = $row_totcnt["position"] - 1;
                                                        }
                                                    }
                                                }

                                                if ($show_all == "yes") {
                                                    $get_trans_sql = "SELECT *, water_transaction.id as transid FROM water_transaction left join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE company_id = " . $id . " " . $sort_str . " LIMIT $rec_pos, 100";
                                                } else {
                                                    $get_trans_sql = "SELECT *, water_transaction.id as transid FROM water_transaction left join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE company_id = " . $id . " " . $sort_str . " LIMIT 0, 100";
                                                }
                                            }
                                        } else {
                                            if (isset($_REQUEST["show_all_trans"])) {
                                                $get_trans_sql = "SELECT *, water_transaction.id as transid FROM water_transaction left join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE company_id = " . $id . " " . $sort_str . " LIMIT 0, 1000";
                                            } else {
                                                $get_trans_sql = "SELECT *, water_transaction.id as transid FROM water_transaction left join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE company_id = " . $id . " " . $sort_str . " LIMIT 0, 100";
                                            }
                                        }

                                        //echo $get_trans_sql;
                                        db();
                                        $tran = db_query($get_trans_sql);
                                        while ($tranlist = array_shift($tran)) {
                                            $vender_nm = "";
                                            $q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '" . $tranlist["vendor_id"] . "'";
                                            db();
                                            $query = db_query($q1);
                                            while ($fetch = array_shift($query)) {
                                                $vender_nm = $fetch['Name'];
                                            }

                                            $tran_status = $tranlist["tran_status"];
                                            switch ($tran_status) {
                                                case 'Pickup':
                                                    $stat = "circle_open.gif";
                                                    break;
                                                case '':
                                                    $stat = "circle_open.gif";
                                                    break;
                                            }
                                            $open = "<img src=\"images/circle_open.gif\" border=\"0\">";
                                            $half = "<img src=\"images/circle__partial.gif\" border=\"0\">";
                                            $full = "<img src=\"images/complete.jpg\" border=\"0\">";

                                        ?>
                                    <tr id='rowid<?php echo $tranlist["transid"]; ?>' bgColor='<?php if ($tranlist["ignore"] == 0) {
                                                                                                            if ($tranlist["transid"] == $_REQUEST["rec_id"]) {
                                                                                                                echo "#CCFFCC";
                                                                                                            } else {
                                                                                                                echo "#e4e4e4";
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo "#EE7373";
                                                                                                        } ?>'>
                                        <td style='width: 98px; height: 13px' class='style1' align="center">
                                            <font size="1">
                                                <?php $the_date_timestamp = date("Y-m-d H:m:i", strtotime($tranlist["transaction_date"]));
                                                        echo $the_date_timestamp; ?>
                                            </font>
                                        </td>
                                        <td style='width: 92px; height: 13px' class='style1' align="center">
                                            <font size="1">
                                                <?php echo $tranlist["last_edited"]; ?>
                                            </font>
                                        </td>
                                        <td style='width: 92px; height: 13px' class='style1' align="center">
                                            <font size="1">
                                                <?php echo $tranlist["invoice_number"]; ?>
                                            </font>
                                        </td>
                                        <td style='width: 92px; height: 13px' class='style1' align="center">
                                            <font size="1">
                                                <?php echo $tranlist["invoice_date"]; ?>
                                            </font>
                                        </td>
                                        <td style='width: 92px; height: 13px' class='style1' align="center">
                                            <font size="1">
                                                <?php echo $vender_nm; ?>
                                            </font>
                                        </td>
                                        <td style='width: 90px; height: 13px' class='style1' align="center">
                                            <a
                                                href="viewCompany-purchasing.php?ID=<?php echo  $_REQUEST["ID"]; ?>&show=watertransactions&company_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&rec_id=<?php echo $tranlist['transid']; ?>&display=water_sort">
                                                <?php if (($tranlist["report_entered"] == 1)) {
                                                            echo $full;
                                                        } elseif ($tranlist["po_date"] != "") {
                                                            echo $half;
                                                        } else {
                                                            echo $open;
                                                        }
                                                        ?>
                                            </a>
                                        </td>
                                        <td style='width: 92px; height: 13px' class='style1' align="center">
                                            <font size="1">$
                                                <?php echo number_format($tranlist["amount"], 2); ?>
                                            </font>
                                        </td>
                                        <td style='width: 92px; height: 13px' class='style1' align="center">
                                            <?php if ($tranlist["scan_report"] != "") {
                                                        $tmppos_1 = strpos($tranlist["scan_report"], "|");
                                                        if ($tmppos_1 != false) {
                                                            $elements = explode("|", $tranlist["scan_report"]);
                                                            for ($i = 0; $i < tep_db_num_rows($elements); $i++) {    ?>
                                            <a target="_blank" href='water_scanreport/<?php echo $elements[$i]; ?>'>
                                                <font size="1">View</font>
                                            </a>
                                            <?php }
                                                        } else {
                                                            ?>
                                            <a target="_blank"
                                                href='water_scanreport/<?php echo $tranlist["scan_report"]; ?>'>
                                                <font size="1">View Attachments</font>
                                            </a>
                                            <?php }
                                                    } ?>
                                        </td>
                                        <td style='width: 92px; height: 13px' class='style1' align="center">
                                            <a href='#' onclick="deletetrans(<?php echo $tranlist[" transid"]; ?>); return
                                                false;">Delete</a>
                                        </td>

                                    </tr>
                                    <?php

                                            $rec_id = $tranlist["transid"];
                                        }
                                        ?>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <tr bgColor="#e4e4e4">
                        <td height="13" colspan="8" class="style1">
                            <p align="center">
                                <font size="1">
                                    <a
                                        href="viewCompany-purchasing.php?ID=<?php echo  $_REQUEST["ID"]; ?>&show=watertransactions&company_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&action=water">Add
                                        Transaction</a>
                                </font>
                        </td>
                    </tr>

                </table>
</form>
<br><br>
<!--Add / Display WATER INITIATIVES list-->
<div style="margin-bottom: 8px;">
    <font size="4" face="arial" color="#333333"><b>WATER INITIATIVES</b></font>
</div>
<!-- <input type="button" value="ADD NEW INITIATIVE" name="add_new_initiative" id="add_new_initiative" onclick="fun_show_new_initiative()" style="margin-bottom: 4px;"> -->
<form method="post" name="new_initiative_frm" id="new_initiative_frm" action="">
    <table width="650" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td colspan="9" bgcolor="#C0CDDA" align="center">
                <font face="Arial, Helvetica, sans-serif" size="2">
                    <strong>New Initiative</strong>
                </font>
            </td>
        </tr>
        <tr>
            <td bgcolor="#C0CDDA">
                <font size="1">
                    Title
                </font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">
                    Details
                </font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">
                    Due date
                </font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">
                    Task Owner
                </font>
            </td>

        </tr>
        <tr>
            <td>
                <textarea name="task_title" id="task_title"></textarea>
            </td>
            <td>
                <textarea name="task_detail" id="task_detail"></textarea>
            </td>
            <td>
                <input type="text" name="due_date" id="due_date" size="10" value="<?php echo  date('m/d/Y') ?>">
                <a href="#"
                    onclick="cal2xxwater.select(document.new_initiative_frm.due_date,'dtanchor2xxwater','MM/dd/yyyy'); return false;"
                    name="dtanchor2xxwater" id="dtanchor2xxwater"><img border="0" src="images/calendar.jpg"></a>
                <div ID="listdivwater"
                    STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                </div>
            </td>
            <td>
                <input type="text" name="task_owner" id="task_owner">
                <input type="hidden" name="init_companyID" id="init_companyID" value="<?php echo isset($b2bid); ?>">
                <input type="hidden" name="init_created_by" id="init_created_by"
                    value="<?php echo  $_COOKIE["userinitials"]; ?>">

            </td>
        </tr>
        <tr align="center">
            <td colspan="7" bgcolor="#C0CDDA">
                <input type="button" id="add_newinitiavite_btn" onclick="add_newinitiavite()" value="Submit" />
                <input type="button" id="cancel_newinitiavite_btn" onclick="newinitiavite_cancel()" value="Cancel" />
            </td>
        </tr>

    </table>
    <br><br>
</form>
<div id="initiative_div">
    <table width="650" border="0" cellspacing="1" cellpadding="1">
        <tr align="center">
            <td colspan="9" bgcolor="#C0CDDA">
                <font face="Arial, Helvetica, sans-serif" size="1"><strong>Active Initiatives</strong></font>
            </td>
        </tr>
        <tr align="center">
            <td bgcolor="#C0CDDA">
                <font size="1">Task ID</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Title</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Details</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Due Date</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Task Owner</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Created On</font>
            </td>

            <td bgcolor="#C0CDDA">&nbsp;</td>
            <td bgcolor="#C0CDDA">&nbsp;</td>
            <td bgcolor="#C0CDDA">&nbsp;</td>
        </tr>
        <?php

            $sql = "SELECT * FROM water_initiatives where companyid = " . isset($b2bid) . " and status = 1 order by taskid";
            db();
            $result = db_query($sql);
            while ($myrowsel = array_shift($result)) {
                $date1 = new DateTime($myrowsel["due_date"]);
                $date2 = new DateTime();

                $days = (strtotime($myrowsel["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
            ?>
        <tr align="center">
            <td bgcolor="#E4E4E4" align="left">
                <font size="1"><?php echo  $myrowsel["taskid"] ?></font>
            </td>

            <td bgcolor="#E4E4E4" align="left">
                <font size="1"><?php echo  $myrowsel["task_title"] ?></font>
            </td>

            <td bgcolor="#E4E4E4" align="left">
                <font size="1"><?php echo  $myrowsel["task_details"] ?></font>
            </td>

            <?php if ($days == 0) { ?>
            <td bgcolor="green">
                <font size="1">
                    <?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?>
                </font>
            </td>
            <?php }

                    if ($days > 0) { ?>
            <td bgcolor="#E4E4E4">
                <font size="1">
                    <?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?>
                </font>
            </td>
            <?php }

                    if ($days < 0) { ?>
            <td bgcolor="red">
                <font size="1">
                    <?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?>
                </font>
            </td>
            <?php } ?>

            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo  $myrowsel["task_owner"] ?></font>
            </td>

            <!--<td bgcolor="#E4E4E4" ><font size="1"><?php echo  $myrowsel["assign_to"] ?></font></td>-->

            <td bgcolor="#E4E4E4">
                <font size="1">
                    <?php echo date("m/d/Y H:i:s", strtotime($myrowsel["task_added_on"]))  . " CT"; ?>
                </font>
            </td>


            <?php if ($_COOKIE["userinitials"] == $myrowsel["task_owner"]) { ?>

            <?php } else { ?>

            <?php } ?>
            <td bgcolor="#E4E4E4" align="middle">
                <input type="button" value="Mark Complete" name="init_markcompl" id="init_markcompl"
                    onclick="init_markcomp(<?php echo  $myrowsel["taskid"]; ?>, <?php echo isset($b2bid); ?>)">
            </td>
            <td bgcolor="#E4E4E4" align="middle">
                <input type="button" value="Edit" name="todo_edit_water"
                    id="todo_edit_water<?php echo  $myrowsel["taskid"]; ?>"
                    onclick="todoitem_edit_water(<?php echo  $myrowsel["taskid"]; ?>, <?php echo isset($b2bid); ?>)">
            </td>
            <td bgcolor="#E4E4E4" align="middle">
                <input type="button" value="Delete" name="todo_del_water"
                    id="todo_del_water<?php echo  $myrowsel["taskid"]; ?>"
                    onclick="todoitem_delete_water(<?php echo  $myrowsel["taskid"]; ?>, <?php echo isset($b2bid); ?>)">
            </td>
        </tr>
        <?php

            }

            ?>
    </table>

    <br>
    <table width="650" border="0" cellspacing="1" cellpadding="1">
        <tr align="center">
            <td colspan="8" bgcolor="#C0CDDA">
                <font face="Arial, Helvetica, sans-serif" size="1"><strong>Initiatives Completed</strong></font>
            </td>
        </tr>
        <tr align="center">
            <td bgcolor="#C0CDDA">
                <font size="1">Task ID</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Title</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Details</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Created On</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Due Date</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Completed Date</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">Task Owner</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">&nbsp;</font>
            </td>
            <td bgcolor="#C0CDDA">
                <font size="1">&nbsp;</font>
            </td>
        </tr>
        <?php

            $sql = "SELECT * FROM water_initiatives where companyid = " . isset($b2bid) . "  and status = 2 order by taskid"; //limit 3
            db();
            $result = db_query($sql);
            while ($myrowsel = array_shift($result)) {
            ?>
        <tr align="center">
            <td bgcolor="#E4E4E4" align="left">
                <font size="1"><?php echo  $myrowsel["taskid"] ?></font>
            </td>
            <td bgcolor="#E4E4E4" align="left">
                <font size="1"><?php echo  $myrowsel["task_title"] ?></font>
            </td>
            <td bgcolor="#E4E4E4">
                <font size="1"><?php echo  $myrowsel["task_details"] ?></font>
            </td>

            <td bgcolor="#E4E4E4">
                <font size="1">
                    <?php echo date("m/d/Y H:i:s", strtotime($myrowsel["task_added_on"]))  . " CT"; ?>
                </font>
            </td>

            <td bgcolor="#E4E4E4">
                <font size="1">
                    <?php echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT"; ?>
                </font>
            </td>

            <td bgcolor="#E4E4E4">
                <font size="1">
                    <?php echo date("m/d/Y", strtotime($myrowsel["completed_date"])) . " CT"; ?>
                </font>
            </td>

            <!--<td bgcolor="#E4E4E4" ><font size="1"><?php //echo $myrowsel["mark_comp_by"];
                                                                ?></font></td>-->
            <td bgcolor="#E4E4E4">
                <font size="1">
                    <?php echo $myrowsel["task_owner"]; ?>
                </font>
            </td>
            <td bgcolor="#E4E4E4" align="middle">
                <input type="button" value="Edit" name="todo_edit_water"
                    id="todo_edit_water<?php echo  $myrowsel["taskid"]; ?>"
                    onclick="todoitem_edit_water(<?php echo  $myrowsel["taskid"]; ?>, <?php echo isset($companyid); ?>)">
            </td>
            <td bgcolor="#E4E4E4" align="middle">
                <input type="button" value="Delete" name="todo_del_water"
                    id="todo_del_water<?php echo  $myrowsel["taskid"]; ?>"
                    onclick="todoitem_delete_water(<?php echo  $myrowsel["taskid"]; ?>, <?php echo isset($companyid); ?>)">
            </td>

        </tr>
        <?php

            }
            ?>


    </table>
</div>
<!--End WATER INITIATIVES list-->
<!-- Display the Pending Invoice details -->
<br>
<?php include("search_result_include_water_box_table.php"); ?>
</td>
</tr>
</table>

</form>

<!-- End Set Up Initial Transaction -->

<!-- Set Up Initial WATER Transaction -->
<!-- Write Initial Fields in Transaction Table Depending on Record Type -->

<?php if ($_GET["action"] == 'water') {

        $company_id = $_GET["id"];
        $id = $_GET["id"];
        $rec_type = $_GET["rec_type"];
        $user = $_COOKIE['userinitials'];
        $rec_id = $_GET["rec_id"];
        $today = date('m/d/y h:i a');

        $qry_newtrans = "INSERT INTO water_transaction SET company_id = '" . $company_id . "', tran_status = 'Pickup'";
        db();
        $res_newtrans = db_query($qry_newtrans);

        $rec_id = tep_db_insert_id();

        // echo $qry_newtrans;
        if (!headers_sent()) {    //If headers not sent yet... then do php redirect
            header('Location: viewCompany-purchasing.php?ID=' . $_REQUEST["ID"] . '&show=watertransactions&company_id=' . $_GET["company_id"] . '&id=' . $_GET["company_id"] . '&proc=View&searchcrit=&rec_type=' . $_GET["rec_type"] . '&proc=View&searchcrit=&rec_id=' . $rec_id . '&display=water_sort');
            exit;
        } else {
            echo "<script type=\"text/javascript\">";
            echo "window.location.href=\"viewCompany-purchasing.php?ID=" . $_REQUEST["ID"] . "&show=watertransactions&company_id=" . $_GET["company_id"] . "&id=" . $_GET["company_id"] . "&proc=View&searchcrit=&rec_type=" . $_GET["rec_type"] . "&proc=View&searchcrit=&rec_id=" . $rec_id . "&display=water_sort\";";
            echo "</script>";
            echo "<noscript>";
            echo "<meta http-equiv=\"refresh\" content=\"0;url=ID=" . $_REQUEST["ID"] . "&show=watertransactions&company_id=" . $_GET["company_id"] . "&id=" . $_GET["company_id"] . "&proc=View&searchcrit=&rec_type=" . $_GET["rec_type"] . "&proc=View&searchcrit=&rec_id=" . $rec_id . "&display=water_sort\" />";
            echo "</noscript>";
            exit;
        } //==== End -- Redirect
    }

    if ($_GET["display"] == 'water_sort') {
        include("search_result_include_water_sort.php");
    }
}

?>