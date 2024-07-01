<?php
// ini_set("display_errors", "1");

// error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>

<head>
    <title>UCBZeroWaste Invoice List Report</title>

    <style>
    .report_title {
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
        font-size: 22px;
        margin-bottom: 4px;
        margin-top: 0px;
    }

    .display_maintitle {
        font-size: 13px;
        padding: 3px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        background: #98bcdf;
        white-space: nowrap;
    }

    .display_title {
        font-size: 12px;
        padding: 3px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        background: #b7d3ef;
        white-space: nowrap;
    }

    .display_table {
        font-size: 11px;
        padding: 3px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        background: #EBEBEB;
    }

    .btnEdit {
        appearance: auto;
        user-select: none;
        white-space: pre;
        align-items: flex-start;
        text-align: center;
        cursor: default;
        color: -internal-light-dark(black, white);
        background-color: -internal-light-dark(rgb(239, 239, 239), rgb(59, 59, 59));
        box-sizing: border-box;
        padding: 1px 6px;
        border-width: 2px;
        border-style: outset;
        border-color: -internal-light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
        border-image: initial;
    }
    </style>

    <script>
    function displarepsorteddata(comp_id, vendor_id, columnno, sortflg) {
        document.getElementById("div_general_forrep").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        MadePayment_val = document.getElementById("ddMadePayment").value;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_general_forrep").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "UCBZeroWaste_All_Invoice_list_child.php?comp_sel=" + comp_id + "&ddMadePayment=" +
            MadePayment_val + "&vendors_dd=" + vendor_id + "&columnno=" + columnno + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function GetFileSize() {
        var fi = document.getElementById('payment_proof_file'); // GET THE FILE INPUT.

        // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
        if (fi.files.length > 0) {
            // RUN A LOOP TO CHECK EACH SELECTED FILE.
            for (var i = 0; i <= fi.files.length - 1; i++) {
                var filenm = fi.files.item(i).name;

                if (filenm.indexOf("#") > 0) {
                    alert("Remove # from Scan file and then upload file!");
                    document.getElementById("payment_proof_file").value = "";
                }
                if (filenm.indexOf("\'") > 0) {
                    alert("Remove \' from " + filenm + " file and then upload file!");
                    document.getElementById("payment_proof_file").value = "";
                }

                var fsize = fi.files.item(i).size; // THE SIZE OF THE FILE.
                if (Math.round(fsize / 1024) > 8000) {
                    alert("Only files with 8mb is allowed.");
                    document.getElementById("payment_proof_file").value = "";
                }
            }
        }
    }

    function editSectionOpen(strInvoiceNumber) {
        var editSectionTbl = document.getElementById("editSectionTbl_" + strInvoiceNumber);
        var btnEditSectionOpen = document.getElementById("btnEditSectionOpen_" + strInvoiceNumber);
        if (editSectionTbl != '') {
            editSectionTbl.style.display = 'revert';
            btnEditSectionOpen.style.display = 'none';
        }
    }

    function cancelSectionClose(strInvoiceNumber) {
        var editSectionTbl = document.getElementById("editSectionTbl_" + strInvoiceNumber);
        var btnCancelSectionClose = document.getElementById("btnCancelSectionClose_" + strInvoiceNumber);
        var btnEditSectionOpen = document.getElementById("btnEditSectionOpen_" + strInvoiceNumber);
        if (editSectionTbl != '') {
            editSectionTbl.style.display = 'none';
            btnEditSectionOpen.style.display = 'revert';
        }

    }
    </script>

    <LINK rel='stylesheet' type='text/css' href='one_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>
<?php

//require ("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

db();



?>

<body>
    <?php include("inc/header.php"); ?>

    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">UCBZeroWaste Invoice List Report
                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">
                        UCBZeroWaste Invoice List Report
                    </span>
                </div>

                <div style="height: 13px;">&nbsp;</div>
            </div>
        </div>

        <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
        <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
        <script LANGUAGE="JavaScript">
        document.write(getCalendarStyles());
        </script>
        <script LANGUAGE="JavaScript">
        var cal2xx = new CalendarPopup("listdiv");
        cal2xx.showNavigationDropdowns();
        var cal3xx = new CalendarPopup("listdiv");
        cal3xx.showNavigationDropdowns();
        </script>

        <form name="frmwater_report_internal" id="frmwater_report_internal" action="UCBZeroWaste_All_Invoice_list.php">

            <table border="0" cellspacing="1" cellpadding="1">
                <tr align="center">
                    <td>
                        Vendor:
                    </td>
                    <td>
                        <select id="vendors_dd" name="vendors_dd" style="width: 230px;">
                            <option value="All">All</option>
                            <?php
                            $vendor_qry = "SELECT * FROM water_vendors where active_flg = 1 order by Name, city, state, zipcode";
                            db();
                            $query = db_query($vendor_qry);
                            $vender_nm = "";
                            //	
                            while ($vendor_row = array_shift($query)) {
                                $vender_nm = $vendor_row['Name'] . " - " . $vendor_row["description"] . " - " . $vendor_row['city'] . ", " . $vendor_row['state'] . " " . $vendor_row['zipcode'];
                            ?>
                            <option value="<?php echo $vendor_row['id']; ?>" <?php if ($_REQUEST["vendors_dd"] == $vendor_row['id']) {
                                                                                        echo " selected ";
                                                                                    } ?>>
                                <?php echo $vender_nm; ?>
                            </option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        &nbsp;&nbsp;&nbsp;Company:
                    </td>
                    <td align="left">
                        <select id="comp_sel" name="comp_sel" style="width: 200px;">
                            <option value="All">All</option>
                            <?php
                            $main_sql = "Select loop_warehouse.id, company_name, b2bid from loop_warehouse inner join water_transaction on loop_warehouse.id = water_transaction.company_id where Active = 1 and loop_warehouse.rec_type = 'Manufacturer' group by loop_warehouse.id order by company_name";
                            //
                            $data_res = db_query($main_sql);
                            while ($data = array_shift($data_res)) {
                                echo "<option value='" . $data["id"] . "' ";
                                if ($_REQUEST["comp_sel"] == $data["id"]) {
                                    echo " selected ";
                                }
                                echo ">" . getnickname($data["company_name"], $data["b2bid"]) . "</option>";
                            }
                            ?>
                        </select>
                    </td>

                    <td>
                        &nbsp;&nbsp;&nbsp;Make or receive payment?
                    </td>
                    <td align="left">
                        <select id="ddMadePayment" name="ddMadePayment" style="width: 200px;">
                            <option value="1" <?php if ($_REQUEST["ddMadePayment"] == '1') {
                                                    echo " selected ";
                                                } ?>>Yes</option>
                            <option value="0" <?php if ($_REQUEST["ddMadePayment"] == '0') {
                                                    echo " selected ";
                                                } ?>>No</option>
                            <option value="All" <?php if ($_REQUEST["ddMadePayment"] == 'All') {
                                                    echo " selected ";
                                                } ?>>Both</option>
                        </select>
                    </td>

                    <td>
                        <input type="submit" id="btnrep" name="btnrep" value="Run Report" />
                    </td>
                </tr>
            </table>
        </form>

        <div id="div_general_forrep" name="div_general_forrep">
            <?php


            if (isset($_REQUEST["btnrep"])) {
            ?>
            <table width="60%" border="0" cellspacing="1" cellpadding="1">

                <tr class="display_maintitle">
                    <td width="290px">Vendor Name&nbsp;<a href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>', 1, 1);"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                            href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>',1, 2);"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
                    <td>UCBZeroWaste Client Name&nbsp;<a href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>', 2, 1);"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                            href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>',2, 2);"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
                    <td>Invoice Number&nbsp;<a href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>', 4, 1);"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                            href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>',4, 2);"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                    <td>Scan of Invoice&nbsp;<a href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>', 11, 1);"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                            href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>',11, 2);"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                    <td>Service End Date&nbsp;<a href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>', 5, 1);"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                            href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>',5, 2);"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
                    <td>Amount&nbsp;<a href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>', 3, 1);"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                            href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>',3, 2);"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                    <td>Made Payment or <br />Received Payment?&nbsp;<a href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>', 6, 1);"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                            href="javascript:void();"
                            onclick="displarepsorteddata('<?php echo $_REQUEST["comp_sel"]; ?>','<?php echo $_REQUEST["vendors_dd"]; ?>',6, 2);"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

                </tr>
                <?php
                    //

                    $flg_once = "no";
                    $swhere_condition = "";
                    $condt = "";
                    if (isset($_REQUEST["comp_sel"])) {
                        if ($_REQUEST["comp_sel"] != "All") {
                            $swhere_condition = " and loop_warehouse.id = " . $_REQUEST["comp_sel"];
                        }
                        $comp_sel = $_REQUEST["comp_sel"];
                    } else {
                        $comp_sel = "All";
                    }
                    $whrMadePayConditn = '';
                    $whrMadePayConditn1 = '';
                    if ($_REQUEST['ddMadePayment'] != 'All') {
                        $whrMadePayConditn1 = ' and water_transaction.make_receive_payment = ' . $_REQUEST['ddMadePayment'];
                        $whrMadePayConditn = ' where water_transaction.make_receive_payment = ' . $_REQUEST['ddMadePayment'];
                    }

                    if (isset($_REQUEST["vendors_dd"])) {
                        if ($_REQUEST["vendors_dd"] != "All") {
                            $vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id where vendor_id = '" . $_REQUEST["vendors_dd"] . "' $whrMadePayConditn1 group by vendor_id";
                            //$Vwhere_condition = " and vendor_id = " . $_REQUEST["vendors_dd"] ;
                        } else {
                            $vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id $whrMadePayConditn group by vendor_id";
                        }
                    } else {
                        $vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id $whrMadePayConditn group by vendor_id";
                        // $vendorsQry = "SELECT water_vendors.id AS vid, water_vendors.*, COUNT(water_transaction.id) AS transaction_count
                        // FROM water_transaction
                        // INNER JOIN water_vendors ON water_transaction.vendor_id = water_vendors.id
                        // $whrMadePayConditn
                        // GROUP BY water_vendors.id";

                    }
                    echo $vendorsQry;
                    db();
                    $v_res = db_query($vendorsQry);




                    while ($data_row = array_shift($v_res)) {

                        $vendorQry = "Select *, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid, water_transaction.id as transid from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
						where vendor_id='" . $data_row["vendor_id"] . "' $whrMadePayConditn1 $swhere_condition group by company_id, water_transaction.id order by water_transaction.company_id";
                        //echo $vendorQry;
                        db();
                        $v_res1 = db_query($vendorQry);
                        //echo "<pre>"; print_r($v_res1); echo "</pre>";
                        $vnumrows = tep_db_num_rows($v_res1);
                        if ($vnumrows > 0) {
                            if ($vnumrows > 1) {
                    ?>
                <tr>
                    <td class="display_table" rowspan="<?php echo $vnumrows ?>">
                        <?php echo $data_row["Name"] . " - " . $data_row["description"] . " - " . $data_row['city'] . ", " . $data_row['state'] . " " . $data_row['zipcode']; ?>
                    </td>
                    <?php
                            } else {
                                ?>
                <tr>
                    <td class="display_table">
                        <?php echo $data_row["Name"] . " - " . $data_row["description"] . " - " . $data_row['city'] . ", " . $data_row['state'] . " " . $data_row['zipcode']; ?>
                    </td>
                    <?php
                            }
                            $row = 1;
                            $isRec = '';
                            while ($rows = array_shift($v_res1)) {
                                ?>

                    <td bgcolor="<?php echo isset($bgcolor); ?>" class="display_table"><a target="_blank"
                            href="viewCompany.php?ID=<?php echo $rows["b2bid"]; ?>&proc=View&searchcrit=&show=watertransactions&rec_type=Manufacturer"><?php echo getnickname($rows["company_name"], $rows["b2bid"]); //echo "<br>".$rows["company_id"]; 
                                                                                                                                                                                                                                                        ?></a>
                    </td>
                    <td class="display_table"><?php echo $rows["invoice_number"]; ?></td>
                    <td class="display_table">
                        <?php if ($rows["scan_report"] != "") {
                                            $tmppos_1 = strpos($rows["scan_report"], "|");
                                            if ($tmppos_1 != false) {
                                                $elements = explode("|", $rows["scan_report"]);
                                                for ($i = 0; $i < tep_db_num_rows($elements); $i++) {    ?>
                        <a target="_blank" href='water_scanreport/<?php echo $elements[$i]; ?>'>
                            <font size="1">View</font>
                        </a><br />
                        <?php }
                                            } else {
                                                ?>
                        <a target="_blank" href='water_scanreport/<?php echo $rows["scan_report"]; ?>'>
                            <font size="1">View Attachments</font>
                        </a>
                        <?php }
                                        } ?>


                    </td>
                    <td class="display_table"><?php if ($rows["invoice_date"] != "") {
                                                                    echo date("m/d/Y", strtotime($rows["invoice_date"]));
                                                                } ?></td>
                    <td class="display_table">$<?php echo number_format($rows["amt"], 2); ?></td>
                    <td class="display_table"><?php if ($rows["made_payment"] == "1") {
                                                                    echo 'Yes';
                                                                } else {
                                                                    echo "No";
                                                                } ?></td>
                    <?php
                                    $row++;
                                    $isRec = 'y';
                                    ?>
                </tr>
                <?php
                            }
                            ?>
                </form>
                <?php
                        }
                        ?>

                <?php

                    }
                    ?>
            </table>

            <?php
            }
            ?>
</body>

</html>