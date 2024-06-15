<script>
function fun_inv_item_add(wid) {
    if (document.getElementById('txtinv_item_add').value == "") {
        alert("Please enter the Inventory B2B ID to add in Inventory.");
        return false;
    }

    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (xmlhttp.responseText == "RecordAdded") {
                alert("Record Added.");
            } else {
                alert("The inventory B2B ID already exists in the backend.");
            }
        }
    }

    xmlhttp.open("POST", "update_inv_item_add.php?action_upd=1&warehouse_id=" + wid + "&inv_b2b_id=" + document
        .getElementById('txtinv_item_add').value, true);
    xmlhttp.send();

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
//display after po
function display_orders_data(tmpcnt, box_id, wid) {
    document.getElementById("light").innerHTML =
        "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
    document.getElementById('light').style.display = 'block';
    document.getElementById('fade').style.display = 'block';

    var selectobject = document.getElementById("after_po" + box_id);
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

    xmlhttp.open("GET", "gaylordstatus_childtable.php?box_id=" + box_id + "&wid=" + wid + "&tmpcnt=" + tmpcnt, true);
    xmlhttp.send();
}

function dubTranPopup(ctrlid) {
    var selectobject = document.getElementById(ctrlid);
    var n_left = f_getPosition(selectobject, 'Left');
    var n_top = f_getPosition(selectobject, 'Top');
    document.getElementById('popup_window').style.display = 'block';
    document.getElementById('popup_window').style.left = n_left + 50 + 'px';
    document.getElementById('popup_window').style.top = n_top + 20 + 'px';
    document.getElementById('popup_window').style.width = '300px';
    document.getElementById('popup_window').style.height = '70px';

    // document.getElementById("popup_window").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 
    const copiesHTML = `
		<form action="duplicate_transaction.php" method="POST" target="_blank">
					<table border="1" align='center' cellpadding='0' cellSpacing="0" bgcolor='#d0cece'>
						<tr>
							<td style="padding: 0 20px;"><strong>Please enter number of transactions</strong></td>
						</tr>
						<tr>
							<td><input style="width:100%;text-align: center" type="number" name="copiedUpto" required></td>
						</tr>
						<tr>
							<td><input style="width:100%" type="submit" value="Submit"></td>
							<input type="hidden" name="transRecordID" value="<?php echo  $_REQUEST["rec_id"] ?>">
							<input type="hidden" name="transRecordCompanyID" value="<?php echo  $_REQUEST["ID"] ?>">
							<input type="hidden" name="transRecordwarehouseID" value="<?php echo  $_REQUEST["warehouse_id"] ?>">
						</tr>
					</table>
				</form>
		`;
    document.getElementById('popup_window').innerHTML = copiesHTML;
}
//
</script>

<?php

$id = "";
$rec_type = "";



if ($_REQUEST["show"] == "transactions") {
    echo "<Font Face='arial' size='4' color='#333333'><b>Transactions</b>";
?>

<table cellSpacing="1" cellPadding="1" border="0">
    <tr>
        <td valign="top">
            <?php if ($_GET["rec_type"] == 'Manufacturer') { ?>

            <table cellSpacing="1" cellPadding="1" border="0" id="table15">
                <tr align="middle">
                    <td bgColor="#c0cdda" colSpan="8">
                        <font size="1" color="#333333">PICKUP</font>
                        </font>
                        <Font Face='arial' size='1'>
                            <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">TRACKER </font>
                            <font size="1">&nbsp;- <a style="color:#0000FF;" href="#">Setup</a></font>
                    </td>
                </tr>

                <tr bgColor="#e4e4e4">
                    <td style="width: 72px; height: 13px" class="style1" align="center">
                        <font size="1">DATE</font>
                    </td>

                    <td style="width: 58px; height: 13px" class="style1" align="center">
                        <font size="1">TRANS ID</font>
                    </td>

                    <td style="width: 58px; height: 13px" class="style1" align="center">
                        <font size="1">TRAILER</font>
                    </td>

                    <td style="width: 68px; height: 13px" class="style1" align="center">
                        <font size="1">STATUS</font>
                    </td>

                    <td style="width: 57px; height: 13px" class="style1" align="center">
                        <font size="1">PICKUP</font>
                    </td>

                    <td style="width: 57px; height: 13px" class="style1" align="center">
                        <font size="1">SORT</font>
                    </td>

                    <td style="width: 57px; height: 13px;" class="style1" align="center">
                        <font size="1">PAYMENT</font>
                    </td>

                    <td style="height: 13px;" class="style1" align="center">
                        <font size="1">AMOUNT</font>
                    </td>
                </tr>

                <tr>
                    <td colspan="8">
                        <div style="height:300px; margin: 0; padding:0; overflow:scroll;">
                            <table cellSpacing="1" cellPadding="1" border="0">
                                <?php

                                        $get_trans_sql = "SELECT * FROM loop_transaction WHERE warehouse_id = " . $id . " ORDER BY transaction_date DESC LIMIT 0, 200";
                                        //echo $get_trans_sql;
                                        db();
                                        $tran = db_query($get_trans_sql);
                                        while ($tranlist = array_shift($tran)) {
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
                                            $full = "<img src=\"images/complete.jpg\" border=\"0\">"; ?>

                                <!--<tr bgColor='<?php //if ($tranlist["id"]==$_REQUEST["rec_id"]) { echo "#CCFFCC";} else { echo "#e4e4e4";}
                                                                ?>'>-->
                                <tr bgColor='<?php if ($tranlist["ignore"] == 0) {
                                                                if ($tranlist["id"] == $_REQUEST["rec_id"]) {
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
                                    <td style='width: 87px; height: 13px' class='style1' align="center">
                                        <font size="1">
                                            <?php echo $tranlist["id"]; ?>
                                        </font>
                                    </td>
                                    <td style='width: 80px; height: 13px' class='style1' align="center">
                                        <font size="1">
                                            <?php echo $tranlist["pr_trailer"]; ?>
                                        </font>
                                    </td>
                                    <td style='width: 87px; height: 13px' class='style1' align="center">
                                        <font size="1">
                                            <?php
                                                        if ($tranlist["usr_file"] != '') {

                                                            echo "Sorted";
                                                        } elseif ($tranlist["sort_entered"] != 0) {

                                                            echo "Sorted";
                                                        } elseif ($tranlist["bol_sort_employee"] != '') {

                                                            echo "Delivered";
                                                        } elseif ($tranlist["cp_employee"] != '') {

                                                            echo "Picked Up";
                                                        } elseif ($tranlist["pa_employee"] != '') {

                                                            echo "Dispatched";
                                                        } elseif ($tranlist["pr_employee"] != '') {

                                                            echo "Requested";
                                                        }

                                                        ?>


                                        </font>
                                    </td>
                                    <td style="width: 87px; height: 13px" class='style1' align="center">
                                        <a href="viewCompany.php?ID=<?php echo  $_REQUEST['ID'] ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&rec_id=<?php echo $tranlist["
                                            id"]; ?>&display=seller_view">
                                            <?php if (($tranlist["dt_employee"] == "") && ($tranlist["pr_employee"] == "") && ($tranlist["pa_employee"] == "") && ($tranlist["cp_employee"] == "") && ($tranlist["bol_employee"] == "")) {
                                                            echo $open;
                                                        } else if ($tranlist["bol_employee"] != "") {
                                                            echo $full;
                                                        } else {
                                                            echo $half;
                                                        } ?>
                                        </a>
                                    </td>
                                    <td style='height: 13px; width: 87px;' class='style1' align="center">
                                        <a href="viewCompany.php?ID=<?php echo  $_REQUEST['ID'] ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&rec_id=<?php echo $tranlist["
                                            id"]; ?>&display=seller_sort">
                                            <?php if (($tranlist["bol_sort_employee"] != "") && ($tranlist["sort_entered"] == "0")) {
                                                            echo $half;
                                                        } else if ($tranlist["sort_entered"] == "1") {
                                                            echo $full;
                                                        } else {
                                                            echo $open;
                                                        }
                                                        ?>
                                        </a>
                                    </td>
                                    <td style='width: 87px; height: 13px' class='style1' align="center">
                                        <center>
                                            <a href="viewCompany.php?ID=<?php echo  $_REQUEST['ID'] ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&rec_id=<?php echo $tranlist["
                                                id"]; ?>&display=seller_payment">
                                                <?php if ($tranlist["pmt_entered"] == "1") {
                                                                echo $full;
                                                            } else {
                                                                echo $open;
                                                            } ?>
                                            </a>
                                        </center>
                                    </td>
                                    <td style='width: 87px; height: 13px' class='style1' align="center">
                                        <font size="1">
                                            <?php echo $tranlist["usr_amount"]; ?>
                                        </font>
                                    </td>
                                </tr>
                                <?php                          } ?>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr bgColor="#e4e4e4">
                    <td height="13" colspan="8" class="style1">
                        <p align="center">
                            <font size="1"><a style="color:#0000FF;"
                                    href="viewCompany.php?ID=<?php echo  $_REQUEST['ID'] ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&action=seller&id=<?php echo $id; ?>">New
                                    Transaction</a></font>
                    </td>
                </tr>

            </table>
            <?php  } ?>


            <?php if ($_GET["rec_type"] == 'Supplier') { ?>
            <table cellSpacing="1" cellPadding="1" border="0" id="table15">
                <tr>
                    <td width="720px" valign="top">
                        <table cellSpacing="1" cellPadding="1" border="0" id="table151">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="9">
                                    <font size="1" color="#333333">ORDER TRACKER </font>
                                    <font size="1"><a style="color:#0000FF;" href="<?php echo $_SERVER['REQUEST_URI'] . "
                                            &show_all_trans=yes"; ?>">Show All Transactions</a></font>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td style="width: 150px; height: 13px" class="style1" align="center">
                                    DATE CREATED
                                </td>
                                <td style="width: 50px; height: 13px" class="style1" align="center">
                                    TRANS ID
                                </td>
                                <td style="width: 88px; height: 13px" class="style1" align="center">ORDERED
                                </td>
                                <td style="width: 87px; height: 13px" class="style1" align="center">SHIPPED
                                </td>
                                <td style="height: 13px; width: 87px;" class="style1" align="center">RECEIVED
                                </td>
                                <td style="height: 13px; width: 87px;" class="style1" align="center">PAID
                                </td>
                                <td style="height: 13px; width: 87px;" class="style1" align="center">VENDOR
                                </td>
                                <td style="height: 13px; width: 100px;" class="style1" align="center">INVOICE
                                </td>
                                <td style="height: 13px; width: 18px;">&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td colspan="9">
                                    <div style="height:300px; margin: 0; padding:0; overflow:scroll;">
                                        <table cellSpacing="1" cellPadding="1" border="0" style="width: 720px;">
                                            <?php
                                                    //echo $id;
                                                    if (isset($_REQUEST["rec_id"])) {
                                                        if (isset($_REQUEST["show_all_trans"])) {
                                                            $get_trans_sql = "SELECT * FROM loop_transaction_buyer WHERE warehouse_id = " . $id . " ORDER BY id desc, transaction_date DESC LIMIT 0, 1000";
                                                        } else {
                                                            $sql_cnt = "SELECT (SELECT COUNT(*) FROM `loop_transaction_buyer` WHERE warehouse_id = '" . $id . "' and id >= '" . $_REQUEST["rec_id"] . "' order by id desc) AS `position`,";
                                                            $sql_cnt .= "(SELECT COUNT(id) FROM `loop_transaction_buyer` WHERE warehouse_id = '" . $id . "' ) AS totcnt";
                                                            $sql_cnt .= " FROM `loop_transaction_buyer` ";
                                                            $sql_cnt .= " WHERE warehouse_id = '" . $id . "' and id =  " . $_REQUEST["rec_id"];
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
                                                                $get_trans_sql = "SELECT * FROM loop_transaction_buyer WHERE warehouse_id = " . $id . " ORDER BY id desc, transaction_date DESC LIMIT $rec_pos, 100";
                                                            } else {
                                                                $get_trans_sql = "SELECT * FROM loop_transaction_buyer WHERE warehouse_id = " . $id . " ORDER BY id desc, transaction_date DESC LIMIT 0, 100";
                                                            }
                                                        }
                                                    } else {
                                                        if (isset($_REQUEST["show_all_trans"])) {
                                                            $get_trans_sql = "SELECT * FROM loop_transaction_buyer WHERE warehouse_id = " . $id . " ORDER BY id desc, transaction_date DESC LIMIT 0, 1000";
                                                        } else {
                                                            $get_trans_sql = "SELECT * FROM loop_transaction_buyer WHERE warehouse_id = " . $id . " ORDER BY id desc, transaction_date DESC LIMIT 0, 100";
                                                        }
                                                    }
                                                    //echo $get_trans_sql;
                                                    db();
                                                    $tran = db_query($get_trans_sql);
                                                    while ($tranlist = array_shift($tran)) {
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
                                            <?php if ($tranlist["ignore"] == 0) {
                                                            if ($tranlist["id"] == $_REQUEST["rec_id"]) {
                                                                $prebgcolor = "#CCFFCC";
                                                            } else {
                                                                $prebgcolor = "#e4e4e4";
                                                            }
                                                        } else {
                                                            $prebgcolor = "#EE7373";
                                                        }

                                                        $existissue = "";
                                                        $curr_issue = "";

                                                        db();
                                                        $sqlissue = db_query("SELECT unqid FROM loop_transaction_buyer_accounting_issue WHERE trans_id = '" . $tranlist["id"] . "'");
                                                        $issuerow = array_shift($sqlissue);
                                                        if (!empty($issuerow)) {
                                                            $existissue = 1;
                                                        }

                                                        db();
                                                        $sqlissue2 = db_query("SELECT unqid FROM loop_transaction_buyer_fulfillment_issue WHERE trans_id = '" . $tranlist["id"] . "'");
                                                        $issuerow2 = array_shift($sqlissue2);
                                                        if (!empty($issuerow2)) {
                                                            $existissue = 1;
                                                        }

                                                        db();
                                                        $sqlissue3 = db_query("SELECT unqid FROM loop_transaction_buyer_order_issue WHERE trans_id = '" . $tranlist["id"] . "'");
                                                        $issuerow3 = array_shift($sqlissue3);
                                                        if (!empty($issuerow3)) {
                                                            $existissue = 1;
                                                        }

                                                        if ($tranlist["accounting_issue"] == 1 || $tranlist["fulfillment_issue"] == 1 || $tranlist["order_issue"] == 1) { // currently active issue
                                                            $curr_issue = 1;
                                                        }

                                                        if ($curr_issue == 1 && $existissue == 1) {
                                                            $colorbg = "#FF0000";
                                                            $issuetxt = "(Issue)";
                                                        } else if ($curr_issue == 1 && $existissue == "") {
                                                            $colorbg = "#FF0000";
                                                            $issuetxt = "(Issue)";
                                                        } else if ($curr_issue == "" && $existissue == 1) {
                                                            $colorbg = "#FFA500";
                                                            $issuetxt = "(Issue Resolved)";
                                                        } else {
                                                            $colorbg = "";
                                                            $issuetxt = "";
                                                        }
                                                        ?>

                                            <!-- <tr style="height:25px;" bgColor='<?php if ($tranlist["ignore"] == 0) {
                                                                                                    if ($tranlist["id"] == $_REQUEST["rec_id"]) {
                                                                                                        echo "#CCFFCC";
                                                                                                    } else {
                                                                                                        echo "#e4e4e4";
                                                                                                    }
                                                                                                } else {
                                                                                                    echo "#EE7373";
                                                                                                } ?>'> -->
                                            <tr style="height:25px;" bgcolor="<?php echo  $prebgcolor; ?>">
                                                <td style='width: 150px; height: 13px' class='style1' align="center">
                                                    <font size="1">
                                                        <?php $the_date_timestamp = date("m/d/Y H:i:s", strtotime($tranlist["transaction_date"]));
                                                                    echo $the_date_timestamp . " CT";
                                                                    if ($tranlist["employee"] != "") {
                                                                        echo " (" . $tranlist["employee"] . ") ";
                                                                    }

                                                                    echo " <b><font style='color:" . $colorbg . "'>" . $issuetxt . "</font></b>";

                                                                    ?>
                                                    </font>
                                                </td>
                                                <td style='width: 50px; height: 13px' class='style1' align="center">
                                                    <font size="1">
                                                        <?php echo $tranlist["id"]; ?>
                                                    </font>
                                                </td>
                                                <td style='width: 90px; height: 13px' class='style1' align="center">
                                                    <a
                                                        href="viewCompany.php?ID=<?php echo  $_REQUEST["ID"]; ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&rec_id=<?php echo $tranlist['id']; ?>&display=buyer_view">
                                                        <?php if (($tranlist["good_to_ship"] == 1)) {
                                                                        echo $full;
                                                                    } elseif ($tranlist["po_date"] != "") {
                                                                        echo $half;
                                                                    } else {
                                                                        echo $open;
                                                                    }
                                                                    ?>
                                                    </a>
                                                </td>
                                                <td style='height: 13px; width: 87px;' class='style1' align="center">
                                                    <Font Face='arial' size='2'>
                                                        <a href="viewCompany.php?ID=<?php echo  $_REQUEST["ID"]; ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&rec_id=<?php echo $tranlist["
                                                            id"]; ?>&display=buyer_ship">
                                                            <?php
                                                                        $bolshipment_sql = "SELECT SUM(loop_bol_files.id) AS A, SUM(loop_bol_files.bol_shipped) AS B,  SUM(loop_bol_files.bol_shipment_received) AS C, SUM(loop_bol_files.bol_shipment_followup) AS D FROM loop_bol_files WHERE trans_rec_id LIKE '" . $tranlist["id"] . "'";

                                                                        db();
                                                                        $bolshipment_qry = db_query($bolshipment_sql);
                                                                        $bolshipment = array_shift($bolshipment_qry);

                                                                        if (($bolshipment["B"] > 0)) {
                                                                            echo $full;
                                                                        } elseif ($bolshipment["A"] > 0) {
                                                                            echo $half;
                                                                        } else {
                                                                            echo $open;
                                                                        } ?>
                                                        </a>
                                                </td>

                                                <!------------- RECEIVED ---------->
                                                <td style='height: 13px; width: 87px;' class='style1' align="center">
                                                    <?php
                                                                $freightupdates = 0;
                                                                $full_flg = 0;
                                                                $qry_1 = "Select loopid, freightupdates from companyInfo Where ID = '" . $_REQUEST["ID"] . "'";
                                                                db_b2b();
                                                                $dt_view_1 = db_query($qry_1);
                                                                while ($rows = array_shift($dt_view_1)) {
                                                                    $freightupdates = $rows["freightupdates"];
                                                                }
                                                                //
                                                                //if ($freightupdates == 0) {
                                                                $sql3ud = "select b2b_survey_ignore, b2b_survey_ignore_by from loop_transaction_buyer where id = '" . $tranlist["id"] . "'";
                                                                db();
                                                                $servey_ignore_res = db_query($sql3ud);
                                                                $servey_ignore_row = array_shift($servey_ignore_res);
                                                                $ignore_servey =    $servey_ignore_row["b2b_survey_ignore"];
                                                                /*	if($ignore_servey>0){
													$full_flg=1;
												}
												else{
													$full_flg=0;
												}
											}
											else{*/
                                                                if ($bolshipment["D"] > 0 || $ignore_servey > 0) {
                                                                    $full_flg = 1;
                                                                } else {
                                                                    $full_flg = 0;
                                                                }
                                                                //}
                                                                ?>
                                                    <a href="viewCompany.php?ID=<?php echo  $_REQUEST["ID"]; ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&rec_id=<?php echo $tranlist["
                                                        id"]; ?>&display=buyer_received">
                                                        <?php
                                                                    //loop_bol_files.bol_shipment_followup
                                                                    if (($full_flg == 1)) {
                                                                        echo $full;
                                                                    }
                                                                    //loop_bol_files.bol_shipment_received
                                                                    elseif ($bolshipment["C"] > 0) {
                                                                        echo $half;
                                                                    } else {
                                                                        echo $open;
                                                                    }

                                                                    ?>
                                                    </a>
                                                </td>

                                                <td style='width: 87px; height: 13px' class='style1' align="center">
                                                    <center>
                                                        <a href="viewCompany.php?ID=<?php echo  $_REQUEST["ID"]; ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&rec_id=<?php echo $tranlist["
                                                            id"]; ?>&display=buyer_payment">
                                                            <?
                                                                        $noinv_val = 0;
                                                                        $sql_noinv = "SELECT no_invoice FROM loop_transaction_buyer WHERE id = " . $tranlist["id"];
                                                                        db();
                                                                        $rec_noinv = db_query($sql_noinv);
                                                                        while ($rec_noinvrow = array_shift($rec_noinv)) {
                                                                            $noinv_val = $rec_noinvrow["no_invoice"];
                                                                        }

                                                                        if ($noinv_val == 0) {
                                                                            $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $tranlist["id"];

                                                                            db();
                                                                            $payment_qry = db_query($payments_sql);
                                                                            $payment = array_shift($payment_qry);
                                                                            //echo "inv_amount " . number_format($tranlist["inv_amount"],2) . " "  . number_format($payment["A"],2) . "<br>"; 
                                                                            if (number_format($tranlist["inv_amount"], 2) == number_format($payment["A"], 2) && $tranlist["inv_amount"] != "") {
                                                                                echo $full;
                                                                            } elseif ($tranlist["inv_amount"] > 0 || $tranlist["inv_amount"] < 0) {
                                                                                echo $half;
                                                                            } else {
                                                                                echo $open;
                                                                            }
                                                                        } else {
                                                                            echo $full;
                                                                        }
                                                                        ?>
                                                        </a>
                                                    </center>
                                                </td>

                                                <!-------------- INVOICES ------------>

                                                <td style='width: 87px; height: 13px' class='style1' align="center">
                                                    <center>
                                                        <a href="viewCompany.php?ID=<?php echo  $_REQUEST["ID"]; ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&rec_id=<?php echo $tranlist["
                                                            id"]; ?>&display=buyer_invoice">
                                                            <?php


                                                                        $payments_sql = "SELECT sum(estimated_cost) as estimated_cost, commission_paid from loop_transaction_buyer inner join loop_transaction_buyer_payments on loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id where loop_transaction_buyer.id = " . $tranlist["id"];

                                                                        db();
                                                                        $payment_qry = db_query($payments_sql);
                                                                        $tmp_circle_status = $open;
                                                                        $pay_infull = "";
                                                                        while ($payment = array_shift($payment_qry)) {
                                                                            if ($payment["estimated_cost"] > 0) {
                                                                                $tmp_circle_status = $half;
                                                                            }

                                                                            if ($payment["commission_paid"] == 1) {
                                                                                $pay_infull = "y";
                                                                            }
                                                                        }
                                                                        if ($pay_infull == "y") {
                                                                            echo $full;
                                                                        } else {
                                                                            echo $tmp_circle_status;
                                                                        }

                                                                        //		echo $payment["A"] . "-" . $payment["B"] . "-" . $payments_sql;
                                                                        ?>
                                                        </a>
                                                    </center>
                                                </td>
                                                <td style='width: 100px; height: 13px' class='style1'>
                                                    <?php
                                                                $dt_view_qry = "SELECT inv_number, inv_amount FROM loop_transaction_buyer WHERE id =" . $tranlist["id"];
                                                                db();
                                                                $dt_view_res = db_query($dt_view_qry);
                                                                $dt_view_row = array_shift($dt_view_res);
                                                                $dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = '" . $tranlist["id"] . "'";
                                                                db();
                                                                $dt_view_res3 = db_query($dt_view_qry3);
                                                                $dt_view_row3 = array_shift($dt_view_res3);
                                                                ?>

                                                    <font size="1">
                                                        Inv #: <?php echo  $dt_view_row["inv_number"]; ?><br>
                                                        Amt : $
                                                        <?php echo  number_format($dt_view_row["inv_amount"], 2); ?><br>
                                                        Bal: $
                                                        <?php echo  number_format((float)str_replace(",", "", $dt_view_row["inv_amount"]) - (float)str_replace(",", "", $dt_view_row3["PAID"]), 2); ?>
                                                    </font>

                                                </td>
                                            </tr>
                                            <?php
                                                        $rec_id = $tranlist["id"];
                                                    }
                                                    ?>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                            <tr bgColor="#e4e4e4">
                                <td height="13" colspan="9" class="style1">
                                    <p align="center">
                                        <font size="1">
                                            <a
                                                href="viewCompany.php?ID=<?php echo  $_REQUEST["ID"]; ?>&show=transactions&warehouse_id=<?php echo $id; ?>&rec_type=<?php echo $rec_type; ?>&proc=View&searchcrit=&id=<?php echo $id; ?>&action=buyer">New
                                                Transaction</a>
                                        </font>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td width="500px">
                        <form method="POST" action="updateIntNotes_mrg_mysqli.php" id="intNotes" name="intNotes">
                            <table border="0" width="600" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="100%" id="msgNote">
                                        <input type=hidden name="id" value="<?php echo  $_REQUEST["ID"]; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" align="left" bgcolor="#E4E4E4">
                                        <?php

                                                $int_notes = "";
                                                $assignedto = "";
                                                $chk_water_flg = 0;
                                                $ucbzw_account_owner = "";
                                                $qry_1 = "Select int_notes, assignedto, ucbzw_flg,ucbzw_account_owner from companyInfo Where ID = '" . $_REQUEST["ID"] . "'";
                                                db_b2b();
                                                $dt_view_1 = db_query($qry_1);
                                                while ($rows = array_shift($dt_view_1)) {
                                                    $int_notes = $rows["int_notes"];
                                                    $assignedto = $rows["assignedto"];
                                                    $chk_water_flg = $rows["ucbzw_flg"];
                                                    $ucbzw_account_owner = $rows["ucbzw_account_owner"];
                                                }

                                                $emp_nm = "";
                                                $qassign = "SELECT name FROM employees WHERE status='Active' and employeeID = '" . $assignedto . "'";
                                                db_b2b();
                                                $dt_view_res_assign = db_query($qassign);
                                                while ($res_assign = array_shift($dt_view_res_assign)) {
                                                    $emp_nm = $res_assign["name"];
                                                }

                                                ?>
                                        <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>UCB
                                                Account Owner:</b>
                                            <?php echo $emp_nm; ?>
                                        </font><br>

                                        <?php if ($chk_water_flg == 1) {
                                                    $emp_nm = "";
                                                    $qassign = "SELECT name FROM employees WHERE status='Active' and employeeID = '" . $ucbzw_account_owner . "'";
                                                    db_b2b();
                                                    $dt_view_res_assign = db_query($qassign);
                                                    while ($res_assign = array_shift($dt_view_res_assign)) {
                                                        $emp_nm = $res_assign["name"];
                                                    }
                                                ?>
                                        <br><br>
                                        <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
                                            <b>UCBZeroWaste Account Owner:</b>
                                            <?php echo $emp_nm; ?>
                                        </font>

                                        <?php    } ?>
                                        <br>
                                        <hr style="color:white;">
                                        <b>Internal Notes </b>
                                        <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br />
                                            (These notes apply to the account in general.)
                                        </font><br>
                                        <textarea rows="16" name="int_notes" cols="3"
                                            style="width:90%"><?php echo  $int_notes ?></textarea> <br /><input
                                            style="cursor:pointer;" type="Submit" value="Update" name="B1">
                                        <?php
                                                if ($_REQUEST["noteadd"] == "yes") {
                                                    echo "<font size='1' color='#ff0000'>Note updated successfully</font>";
                                                }
                                                ?>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <br />

                    </td>
                </tr>
            </table>

            <!-- Display the Pending Invoice details -->

            <!-- Display the Pending Invoice details -->

            <?php } ?>

            <br>
            <table cellSpacing="1" cellPadding="1" border="0">
                <tr>
                    <td valign="top" align="center" bgColor="#c0cdda">
                        <span style="display:inline-block; width:740px; text-align:middle;">
                            <font size="1">INVENTORY ITEMS</font>
                            <a href='#'
                                onclick="expandinvitems1(1, <?php echo  $id ?>, <?php echo  $_REQUEST["ID"] ?>, '<?php echo  $rec_type ?>'); return false;">Expand</a>/<a
                                href='#' onclick="expandinvitems(2); return false;">Collapse</a>
                        </span>
                        <div id='div_inv_items1'>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td valign="top" align="center" bgColor="#c0cdda">
                        <font size="1">Add Inventory Item <br>
                            Inventory B2B ID:<input type="text" id="txtinv_item_add" name="txtinv_item_add" value="" />
                            <input type="button" id="btninv_item_add" name="btninv_item_add" value="Add"
                                onclick="fun_inv_item_add(<?php echo  $id ?>)" />
                        </font>
                    </td>
                </tr>

            </table>
            <?php //if ($_GET["rec_type"] != ''){

                ?>
            <?php //include ("search_result_include_box_table_mrg.php"); 
                ?>

            <table cellSpacing="1" cellPadding="1" border="0" style="width: 740px">
                <br>
                <tr>
                    <td valign="top">
                        <!-- To set the mark record inactive-->
                        <?php

                            if (($_REQUEST["rec_type"] == "Supplier")) {
                                $rec_active_flg = 0;
                                if ($_REQUEST["rec_id"] != "") {
                                    $sql_preord = "SELECT `ignore` as rec_active_flg FROM loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
                                    //echo $sql_preord;
                                    db();
                                    $rec_preord = db_query($sql_preord);
                                    while ($rec_preordrow = array_shift($rec_preord)) {
                                        $rec_active_flg = $rec_preordrow["rec_active_flg"];

                                        if ($rec_active_flg == 0) { ?>
                        <form METHOD="POST" action="update_rec_active_flg.php">
                            <input type="hidden" name="rec_id_inactiveflg" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="warehouse_id" value="<?php echo  $id; ?>">

                            <input type="hidden" name="rec_active_flg" value="yes">

                            <input style="cursor:pointer;" type="submit" id="updateignoreflg"
                                value="Cancel Transaction" />
                        </form>
                        <?php } else { ?>
                        <form METHOD="POST" action="update_rec_active_flg.php">
                            <input type="hidden" name="rec_id_inactiveflg" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="warehouse_id" value="<?php echo  $id; ?>">
                            <input type="hidden" name="rec_active_flg" value="no">

                            <input style="cursor:pointer;" type="submit" id="updateignoreflg"
                                value="Re-Activate Transaction" />
                        </form>
                        <?php }
                                    }
                                }
                            } ?>
                        <!-- To set the mark record inactive-->
                    </td>
                    <td valign="top">
                        <!-- To set the Pre_order-->
                        <?php

                            if (($_REQUEST["rec_type"] == "Supplier")) {
                                $Preorder_val = 0;
                                if ($_REQUEST["rec_id"] != "") {
                                    $sql_preord = "SELECT Preorder FROM loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
                                    //echo $sql_preord;
                                    db();
                                    $rec_preord = db_query($sql_preord);
                                    while ($rec_preordrow = array_shift($rec_preord)) {
                                        $Preorder_val = $rec_preordrow["Preorder"];

                                        if ($Preorder_val == 0) { ?>
                        <form METHOD="POST" action="update_preorder_mrg.php">
                            <input type="hidden" name="rec_id_preorder" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="Preorder" value="yes">

                            <input style="cursor:pointer;" type="submit" id="updatePreorder"
                                value="Mark as Pre-Order" />
                        </form>
                        <?php } else { ?>
                        <form METHOD="POST" action="update_preorder_mrg.php">
                            <input type="hidden" name="rec_id_preorder" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="Preorder" value="no">

                            <input style="cursor:pointer;" type="submit" id="updatePreorder"
                                value="UnMark as Pre-Order" />
                        </form>
                        <?php }
                                    }
                                }
                            } ?>
                        <!-- To set the Pre_order-->
                    </td>


                    <td valign="top" id="frm_unavailable_td">
                        <?php

                            if (($_REQUEST["rec_type"] == "Supplier")) {
                                if ($_REQUEST["rec_id"] != "") {
                                    $sql_preord = "SELECT mark_unavailable FROM loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
                                    //echo $sql_preord;
                                    db();
                                    $rec_preord = db_query($sql_preord);
                                    while ($rec_preordrow = array_shift($rec_preord)) {
                                        $mark_unavailable_val = $rec_preordrow["mark_unavailable"];

                                        if ($mark_unavailable_val == 0) { ?>
                        <form METHOD="POST" action="update_mark_unavailable.php">
                            <input type="hidden" name="mark_unavailable_rec_id"
                                value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="mark_unavailable_flg" value="yes">

                            <input style="cursor:pointer;" type="submit" id="mark_unavailable"
                                value="Mark as Unavailable" />
                        </form>
                        <?php } else { ?>
                        <form METHOD="POST" action="update_mark_unavailable.php">
                            <input type="hidden" name="mark_unavailable_rec_id"
                                value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="mark_unavailable_flg" value="no">

                            <input style="cursor:pointer;" type="submit" id="mark_unavailable"
                                value="Unmark as Unavailable" />
                        </form>
                        <?php }
                                    }
                                }
                            } ?>
                    </td>

                    <td valign="top">
                        <!-- To set the Recycling-->
                        <?php

                            if (($_REQUEST["rec_type"] == "Supplier")) {
                                $recycling_flg = 0;
                                if ($_REQUEST["rec_id"] != "") {
                                    $sql_preord = "SELECT recycling_flg FROM loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
                                    //echo $sql_preord;
                                    db();
                                    $rec_preord = db_query($sql_preord);
                                    while ($rec_preordrow = array_shift($rec_preord)) {
                                        $recycling_flg = $rec_preordrow["recycling_flg"];

                                        if ($recycling_flg == 0) { ?>
                        <form METHOD="POST" action="update_recycling_flg.php">
                            <input type="hidden" name="rec_id_preorder" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="recycling_flg" value="yes">

                            <input style="cursor:pointer;" type="submit" id="updateRecycling"
                                value="Mark as Recycling" />
                        </form>
                        <?php } else { ?>
                        <form METHOD="POST" action="update_recycling_flg.php">
                            <input type="hidden" name="rec_id_preorder" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="recycling_flg" value="no">

                            <input style="cursor:pointer;" type="submit" id="updateRecycling"
                                value="UnMark as Recycling" />
                        </form>
                        <?php }
                                    }
                                }
                            } ?>
                        <!-- To set the Recycling-->
                    </td>

                    <td valign="top">
                        <!-- Duplicate Transaction? -->
                        <button id="duplicateTransaction" type="button" onclick="dubTranPopup('duplicateTransaction')"
                            data-target="#duplicateTransaction">Duplicate Transaction?</button>
                        <!-- Duplicate Transaction? -->
                    </td>
                    <td valign="top">&nbsp;</td>
                </tr>

                <tr>
                    <td valign="top" id="frm_fulfillment_issue_td">
                        <?php

                            if (($_REQUEST["rec_type"] == "Supplier")) {
                                if ($_REQUEST["rec_id"] != "") {
                                    $sql_preord = "SELECT fulfillment_issue FROM loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
                                    //echo $sql_preord;
                                    db();
                                    $rec_preord = db_query($sql_preord);
                                    while ($rec_preordrow = array_shift($rec_preord)) {
                                        $order_issue_val = $rec_preordrow["fulfillment_issue"];

                                        if ($order_issue_val == 0) { ?>
                        <form METHOD="POST" action="update_fulfillment_issue.php">
                            <input type="hidden" name="rec_id_fulfillment_issue"
                                value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="fulfillment_issue_flg" value="yes">

                            <input style="cursor:pointer;" type="button" id="updatefulfillment_issue"
                                value="Mark as Fulfillment Issue"
                                onclick="show_popup_general('fulfillment_issue', 1, 'updatefulfillment_issue', <?php echo  $_REQUEST["rec_id"]; ?>, <?php echo  $_REQUEST["id"]; ?>)" />
                        </form>
                        <?php } else { ?>
                        <form METHOD="POST" action="update_fulfillment_issue.php">
                            <input type="hidden" name="rec_id_fulfillment_issue"
                                value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="fulfillment_issue_flg" value="no">

                            <input style="cursor:pointer;" type="button" id="updatefulfillment_issue"
                                value="UnMark as Fulfillment Issue"
                                onclick="show_popup_general('fulfillment_issue', 0, 'updatefulfillment_issue', <?php echo  $_REQUEST["rec_id"]; ?>, <?php echo  $_REQUEST["id"]; ?>)" />
                        </form>
                        <?php }
                                    }
                                }
                            } ?>
                    </td>

                    <td valign="top" id="frm_orderissue_td">
                        <?php

                            if (($_REQUEST["rec_type"] == "Supplier")) {
                                if ($_REQUEST["rec_id"] != "") {
                                    $sql_preord = "SELECT order_issue FROM loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
                                    //echo $sql_preord;
                                    db();
                                    $rec_preord = db_query($sql_preord);
                                    while ($rec_preordrow = array_shift($rec_preord)) {
                                        $order_issue_val = $rec_preordrow["order_issue"];

                                        if ($order_issue_val == 0) { ?>
                        <form METHOD="POST" action="update_order_issue.php">
                            <input type="hidden" name="rec_id_order_issue" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="order_issue_flg" value="yes">

                            <input style="cursor:pointer;" type="button" id="updateorder_issue"
                                value="Mark as Order Issue"
                                onclick="show_popup_general('orderissue', 1, 'updateorder_issue', <?php echo  $_REQUEST["rec_id"]; ?>, <?php echo  $_REQUEST["id"]; ?>)" />
                        </form>
                        <?php } else { ?>
                        <form METHOD="POST" action="update_order_issue.php">
                            <input type="hidden" name="rec_id_order_issue" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="order_issue_flg" value="no">

                            <input style="cursor:pointer;" type="button" id="updateorder_issue"
                                value="UnMark as Order Issue"
                                onclick="show_popup_general('orderissue', 0, 'updateorder_issue', <?php echo  $_REQUEST["rec_id"]; ?>, <?php echo  $_REQUEST["id"]; ?>)" />
                        </form>
                        <?php }
                                    }
                                }
                            } ?>
                    </td>


                    <td valign="top" id="frm_accounting_issue_td">
                        <?php

                            if (($_REQUEST["rec_type"] == "Supplier")) {
                                if ($_REQUEST["rec_id"] != "") {
                                    $sql_preord = "SELECT accounting_issue FROM loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
                                    //echo $sql_preord;
                                    db();
                                    $rec_preord = db_query($sql_preord);
                                    while ($rec_preordrow = array_shift($rec_preord)) {
                                        $accounting_issue_val = $rec_preordrow["accounting_issue"];

                                        if ($accounting_issue_val == 0) { ?>
                        <form METHOD="POST" action="update_accounting_issue.php">
                            <input type="hidden" name="rec_id_accounting_issue"
                                value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="accounting_issue_flg" value="yes">

                            <input style="cursor:pointer;" type="button" id="updateaccounting_issue"
                                value="Mark as Accounting Issue"
                                onclick="show_popup_general('accounting_issue', 1, 'updateaccounting_issue', <?php echo  $_REQUEST["rec_id"]; ?>, <?php echo  $_REQUEST["id"]; ?>)" />
                        </form>
                        <?php } else { ?>
                        <form METHOD="POST" action="update_accounting_issue.php">
                            <input type="hidden" name="rec_id_accounting_issue"
                                value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="accounting_issue_flg" value="no">

                            <input style="cursor:pointer;" type="button" id="updateaccounting_issue"
                                value="UnMark as Accounting Issue"
                                onclick="show_popup_general('accounting_issue', 0, 'updateaccounting_issue', <?php echo  $_REQUEST["rec_id"]; ?>, <?php echo  $_REQUEST["id"]; ?>)" />
                        </form>
                        <?php }
                                    }
                                }
                            } ?>
                    </td>

                    <td valign="top">
                        <!-- To set the UCBZeroWaste-->
                        <?php

                            if (($_REQUEST["rec_type"] == "Supplier")) {
                                $UCBZeroWaste_flg = 0;
                                if ($_REQUEST["rec_id"] != "") {
                                    $sql_preord = "SELECT UCBZeroWaste_flg FROM loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
                                    //echo $sql_preord;
                                    db();
                                    $rec_preord = db_query($sql_preord);
                                    while ($rec_preordrow = array_shift($rec_preord)) {
                                        $UCBZeroWaste_flg = $rec_preordrow["UCBZeroWaste_flg"];

                                        if ($UCBZeroWaste_flg == 0) { ?>
                        <form METHOD="POST" action="update_UCBZeroWaste_flg.php">
                            <input type="hidden" name="rec_id_UCBZeroWaste" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="compid_UCBZeroWaste" value="<?php echo  $_REQUEST["ID"]; ?>">
                            <input type="hidden" name="UCBZeroWaste_flg" value="yes">

                            <input style="cursor:pointer;" type="submit" id="updateUCBZeroWaste"
                                value="Mark as UCBZeroWaste" />
                        </form>
                        <?php } else { ?>
                        <form METHOD="POST" action="update_UCBZeroWaste_flg.php">
                            <input type="hidden" name="rec_id_UCBZeroWaste" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                            <input type="hidden" name="compid_UCBZeroWaste" value="<?php echo  $_REQUEST["ID"]; ?>">
                            <input type="hidden" name="UCBZeroWaste_flg" value="no">

                            <input style="cursor:pointer;" type="submit" id="updateUCBZeroWaste1"
                                value="UnMark as UCBZeroWaste" />
                        </form>
                        <?php }
                                    }
                                }
                            } ?>
                        <!-- End To set the UCBZeroWaste-->
                    </td>
                </tr>
                <tr>
                    <td colSpan="4" id="frm_trans_notes_td">
                        <?php if ($_GET["display"] != '') {
                                include("search_result_include_crm_mrg_mysqli.php");
                            }
                            ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php

    //}

    ?>

<!-- Set Up Initial Transaction -->
<!-- Write Initial Fields in Transaction Table Depending on Record Type -->

<?php if ($_GET["action"] == 'seller') {

        $warehouse_id = $_GET["id"];
        $id = $_GET["id"];
        $rec_type = $_GET["rec_type"];
        $user = $_COOKIE['userinitials'];
        $rec_id = $_GET["rec_id"];
        $trailer = $_GET["pr_trailer"];
        $today = date('m/d/y h:i a');

        $qry_newtrans = "INSERT INTO loop_transaction SET warehouse_id = '" . $warehouse_id . "', rec_type = '" . $rec_type . "', start_date = '" . $today . "', trans_type = 'Seller', tran_status = 'Pickup', employee = '" . $user . "'";
        db();
        $res_newtrans = db_query($qry_newtrans);
        // echo $qry_newtrans;
        if (!headers_sent()) {    //If headers not sent yet... then do php redirect
            header('Location: viewCompany.php?ID=' . $_REQUEST["ID"] . '&show=transactions&id=' . $_GET["warehouse_id"] . '&proc=View&searchcrit=&rec_type=' . $_GET["rec_type"]);
            exit;
        } else {
            echo "<script type=\"text/javascript\">";
            echo "window.location.href=\"viewCompany.php?ID=" . $_REQUEST["ID"] . "&show=transactions&id=" . $_GET["warehouse_id"] . "&rec_type=" . $_GET["rec_type"] . "&proc=View&searchcrit=\";";
            echo "</script>";
            echo "<noscript>";
            echo "<meta http-equiv=\"refresh\" content=\"0;url=viewCompany.php?ID=" . $_REQUEST["ID"] . "&show=transactions&id=" . $_GET["warehouse_id"] . "&rec_type=" . $_GET["rec_type"] . "&proc=View&searchcrit=\" />";
            echo "</noscript>";
            exit;
        } //==== End -- Redirect
    }
    ?>


<!-- End Set Up Initial Transaction -->

<!-- Begin Transaction Functions -->

<!-- CONDITIONAL - Begin seller_view Functions if record is Seller -->

<?php if ($_GET["display"] == 'seller_view') {

        $rec_id = $_REQUEST["rec_id"];
        include("search_result_include_seller_view_mrg_purchasing.php");
    } ?>

<!-- End seller_view Functions -->



<!-- CONDITIONAL - Begin seller_view Functions if record is Seller -->

<?php if ($_GET["display"] == 'seller_sort') {

        include("search_result_include_seller_sort_mrg_purchasing.php");
    }

    ?>

<!-- End seller_view Functions -->


<!-- CONDITIONAL - Begin seller_view Functions if record is Seller -->

<?php if ($_GET["display"] == 'seller_payment') {

        include("search_result_include_seller_payment_mrg.php");
    } ?>

<!-- End seller_view Functions -->

<!-- Set Up Initial Buyer Transaction -->
<!-- Write Initial Fields in Transaction Table Depending on Record Type -->

<?php if ($_GET["action"] == 'buyer') {

        $warehouse_id = $_GET["id"];
        $id = $_GET["id"];
        $rec_type = $_GET["rec_type"];
        $user = $_COOKIE['userinitials'];
        $rec_id = $_GET["rec_id"];
        $today = date('m/d/y h:i a');

        $last_load = 1;
        $last_load_qry = "SELECT load_number FROM loop_transaction_buyer WHERE warehouse_id = " . $warehouse_id . " order by load_number desc limit 1";
        db();
        $last_load_res = db_query($last_load_qry);
        while ($last_load_row = array_shift($last_load_res)) {
            $last_load = $last_load_row["load_number"];
            $last_load = $last_load + 1;
        }


        $qry_newtrans = "INSERT INTO loop_transaction_buyer SET load_number = '" . $last_load . "', warehouse_id = '" . $warehouse_id . "', rec_type = '" . $rec_type . "', start_date = '" . $today . "', trans_type = 'Seller', tran_status = 'Pickup', employee = '" . $user . "'";
        db();
        $res_newtrans = db_query($qry_newtrans);

        // echo $qry_newtrans;
        if (!headers_sent()) {    //If headers not sent yet... then do php redirect
            header('Location: viewCompany.php?ID=' . $_REQUEST["ID"] . '&show=transactions&id=' . $_GET["warehouse_id"] . '&proc=View&searchcrit=&rec_type=' . $_GET["rec_type"]);
            exit;
        } else {
            echo "<script type=\"text/javascript\">";
            echo "window.location.href=\"viewCompany.php?ID=" . $_REQUEST["ID"] . "&show=transactions&id=" . $_GET["warehouse_id"] . "&rec_type=" . $_GET["rec_type"] . "&proc=View&searchcrit=\";";
            echo "</script>";
            echo "<noscript>";
            echo "<meta http-equiv=\"refresh\" content=\"0;url=viewCompany.php?ID=" . $_REQUEST["ID"] . "&show=transactions&id=" . $_GET["warehouse_id"] . "&rec_type=" . $_GET["rec_type"] . "&proc=View&searchcrit=\" />";
            echo "</noscript>";
            exit;
        } //==== End -- Redirect
    }

    if ($_GET["display"] == 'buyer_view') {

        include("search_result_include_buyer_view_mrg_mysqli.php");
    }

    if ($_GET["display"] == 'buyer_ship') {

        include("search_result_include_buyer_ship_mrg_mysqli.php");
    }

    if ($_GET["display"] == 'buyer_received') {

        include("search_result_include_buyer_received_mrg_mysqli.php");
    }

    if ($_GET["display"] == 'buyer_payment') {

        include("search_result_include_buyer_payment_mrg_mysqli.php");
    }

    if ($_GET["display"] == 'buyer_invoice') {

        include("search_result_include_buyer_invoice_mrg_mysqli.php");
    }

    if ($_GET["display_b2b"] == 'quote') {

        include("search_result_quote_new_mrg_mysqli.php");
    }

    if ($_GET["display_b2b"] == 'view_assignments') {

        include("search_result_view_assignments_mrg_mysqli.php");
    }

    if ($_GET["display_b2b"] == 'view_notes_crm') {
        include("search_result_view_notes_crm_mrg_mysqli.php");
    } ?>

<?php

}

?>