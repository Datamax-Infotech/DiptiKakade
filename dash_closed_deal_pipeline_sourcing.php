<?php
//loop_test_final2.php
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

$initials = $_REQUEST['initials'];

$emp_sql = "select employeeID as id, name from employees where initials = '$initials'";
db_b2b();
$emp_res = db_query($emp_sql);
$emprw = array_shift($emp_res);


$time = strtotime(Date('Y-m-d'));
if (date('l', $time) != "Sunday") {
    $st_sunday = strtotime('last sunday', $time);
} else {
    $st_sunday = $time;
}

$st_saturday = strtotime('+6 days', $st_sunday);

$st_date = Date('Y-m-d', $st_sunday);
$end_date = Date('Y-m-d', $st_saturday);
$st_date = '2023-07-02';
$end_date = '2023-07-08';


?>
<script>
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

    document.getElementById('light').style.left = '100px';
    document.getElementById('light').style.top = elementTop + 100 + 'px';
}


function close_div() {
    document.getElementById('light').style.display = 'none';
}
</script>

<style>
.white_content {
    display: none;
    position: absolute;
    padding: 5px;
    border: 2px solid black;
    background-color: white;
    overflow: auto;
    height: 400px;
    width: 1000px;
    z-index: 1002;
    margin: 0px 0 0 0px;
    padding: 10px 10px 10px 10px;
    border-color: black;
    border-width: 2px;
    overflow: auto;
}
</style>

<div id="light" class="white_content"></div>
<table cellSpacing="1" cellPadding="1" border="0" width="500">
    <tr>
        <td bgColor='#CFE7FF'>&nbsp;</td>
        <td bgColor='#CFE7FF' align="center">Deals</td>
        <td bgColor='#CFE7FF' align="center">Amount</td>
    </tr>

    <?php

    $str_box_list_ids = "";
    $summtd_SUMPO = 0;
    $summtd_dealcnt = 0;
    $profit_val_org = 0;
    $str_box_list_transids = "";

    db();
    $qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id FROM loop_invoice_items WHERE box_item_founder_emp_id=" . $emprw['id']);

    while ($row_rs_tmprs = array_shift($qry)) {
        $str_box_list_ids .= $row_rs_tmprs["id"] . ",";
        $str_box_list_transids .= $row_rs_tmprs["trans_rec_id"] . ",";
    }
    if ($str_box_list_ids != "") {
        $str_box_list_ids = substr($str_box_list_ids, 0, strlen($str_box_list_ids) - 1);
    }
    if ($str_box_list_transids != "") {
        $str_box_list_transids = substr($str_box_list_transids, 0, strlen($str_box_list_transids) - 1);
    }

    $sql = "Select ID, companyID as company_id, round(quote_total,0) as quote_total from quote where quoteType = 'PO' and quoteDate between '" . $st_date . "' and '" . $end_date . " 23:59:59' and rep = " . $emprw['id'];

    db_b2b();
    $qres = db_query($sql);
    //echo "<pre>"; print_r($qres); echo "</pre>";
    $numberofrecords = 0;
    $totalquotevalue = 0;
    $quoteIDs = "";
    while ($qrow = array_shift($qres)) {
        $numberofrecords = $numberofrecords + 1;
        $totalquotevalue = $totalquotevalue + $qrow['quote_total'];
        $quoteIDs .= $qrow['ID'] . ",";
    }
    if ($quoteIDs != "") {
        $quoteIDs = substr($quoteIDs, 0, strlen($quoteIDs) - 1);
    }
    ?>
    <tr>
        <td bgColor='#ABC5DF'>PO Not Entered Yet</td>
        <td bgColor='#E4EAEB' align="right"><?php echo $numberofrecords; ?></td>
        <td bgColor='#E4EAEB' align="right"><a target='_blank'
                href='report_purchasing_show_list.php?quote_amount=yes&purchasing=yes&date_from_val=<?php echo $st_date; ?>&date_to_val=<?php echo $end_date; ?>&crmemp=<?php echo $initials; ?>&b2bempid=<?php echo $emprw['id']; ?>'>$<?php echo number_format($totalquotevalue, 0); ?></a>
        </td>
    </tr>
    <?php

    $lisoftrans = $lisoftrans ?? '';

    $tbl_color = "#E4EAEB";
    $lisoftrans .= "<span><table cellSpacing='1' cellPadding='1' width='800'>";
    $lisoftrans .= "<tr><td bgColor='#ABC5DF'>Employee</td><td bgColor='#ABC5DF'>Company Name</td><td bgColor='#ABC5DF'>Box Description</td><td bgColor='#ABC5DF'>Quantity</td><td bgColor='#ABC5DF'>Price</td><td bgColor='#ABC5DF'>Total</td><td bgColor='#ABC5DF'>Profit Value </td><td bgColor='#ABC5DF'>Profit Percentage</td></tr>";
    if ($str_box_list_ids != "") {
        $row_no = 0;
        $tmp_trans_id = "";
        $vendor_b2b_rescue = 0;
        $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_invoice_details.total as loop_inv_amount 
				FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id 
				inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_bol_tracking.trans_rec_id 
				where loop_transaction_buyer.ignore = 0 and loop_invoice_details.trans_rec_id in (" . $str_box_list_transids . ") and loop_invoice_details.timestamp between '" . $st_date . "' and '" . $end_date . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");

        while ($row_rs_tmprs = array_shift($qry)) {

            if ($row_rs_tmprs["trans_rec_id"] != $tmp_trans_id) {
                $row_no    = 0;
            } else {
                $row_no    = $row_no + 1;
            }

            $box_qry = "SELECT * FROM loop_boxes WHERE id = " . $row_rs_tmprs["box_id"];
            $box_res = db_query($box_qry);
            $boxdesc = "";
            while ($box_row = array_shift($box_res)) {

                $boxdesc = round($box_row["blength"]) . " ";
                if ($box_row["blength_frac"] != "")
                    $boxdesc .= $box_row["blength_frac"] . " ";
                $boxdesc .= "x " . round($box_row["bwidth"]) . " ";
                if ($box_row["bwidth_frac"] != "")
                    $boxdesc .= $box_row["bwidth_frac"] . " ";
                $boxdesc .= "x " . round($box_row["bdepth"]) . " ";
                if ($box_row["bdepth_frac"] != "")
                    $boxdesc .= $box_row["bdepth_frac"] . " ";
                $boxdesc .= $box_row["bdescription"];
                $vendor_b2b_rescue = $box_row["vendor_b2b_rescue"];
            }

            $price = 0;
            $total = 0;
            $quantity = 0;
            $invoice_amt = 0;
            $box_desc = "";
            $invoice_amt_ind = 0;
            $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and loop_box_id = '" . $row_rs_tmprs["box_id"] . "'");

            while ($row_rs_data_main = array_shift($qry_box_main)) {
                $quantity = $quantity + $row_rs_data_main["quantity"];
                $price = $row_rs_data_main["price"];
                $total = $total + str_replace(",", "", $row_rs_data_main["total"]);
                $box_desc = $row_rs_data_main['description'];
                $invoice_amt_ind = $invoice_amt_ind + $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
            }

            if ($quantity > 0) {

                $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ");
                while ($row_rs_data_main = array_shift($qry_box_main)) {
                    $invoice_amt += $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                }

                $gr_total = str_replace(",", "", $total);

                $summtd_SUMPO = $summtd_SUMPO + $gr_total;

                $b2bid = 0;
                $company_name = "";
                $wid = 0;
                $inv_number = "";
                $double_checked = 0;
                $virtual_inventory_trans_id = 0;
                $virtual_inventory_company_id = 0;
                $q1 = "SELECT loop_warehouse.b2bid, inv_number , inv_amount, loop_warehouse.id as wid, virtual_inventory_company_id, virtual_inventory_trans_id, double_checked, company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id where loop_transaction_buyer.id = " . $row_rs_tmprs["trans_rec_id"];
                $query = db_query($q1);
                while ($fetch = array_shift($query)) {
                    $b2bid = $fetch['b2bid'];
                    $wid = $fetch['wid'];
                    $double_checked = $fetch['double_checked'];
                    $company_name = $fetch['company_name'];
                    $inv_number = $fetch['inv_number'];
                    $inv_amount = $fetch["inv_amount"];
                    $virtual_inventory_trans_id = $fetch['virtual_inventory_trans_id'];
                    $virtual_inventory_company_id = $fetch['virtual_inventory_company_id'];
                }

                $finalpaid_amt = 0;

                $invoice_amt = 0;
                if ($finalpaid_amt > 0) {
                    $invoice_amt = str_replace(",", "", $finalpaid_amt);
                }
                if ($finalpaid_amt == 0 && isset($inv_amount) > 0) {
                    $inv_amount = $inv_amount ?? '';
                    $invoice_amt = str_replace(",", "", $inv_amount);
                }
                if ($finalpaid_amt == 0 && isset($inv_amount) == 0 && $row_rs_tmprs["loop_inv_amount"] > 0) {
                    $invoice_amt = str_replace(",", "", $row_rs_tmprs["loop_inv_amount"]);
                }


                $nickname = get_nickname_val($company_name, $b2bid);
                $nickname_supplier = "";

                $supplier_b2bid = 0;
                $supplier_wid = 0;
                $supplier_company_name = "";
                if ($virtual_inventory_trans_id != -1) {
                    $q1 = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, company_name FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id where loop_transaction.id = " . $virtual_inventory_trans_id;
                    $query = db_query($q1);
                    while ($fetch = array_shift($query)) {
                        $supplier_b2bid = $fetch['b2bid'];
                        $supplier_wid = $fetch['wid'];
                        $supplier_company_name = $fetch['company_name'];
                    }

                    $nickname_supplier = get_nickname_val($supplier_company_name, $supplier_b2bid);

                    $supp_nm = $virtual_inventory_trans_id . "-" . $nickname_supplier;
                } else {
                    $virtual_inventory_trans_id = "";
                    $supp_nm = "";

                    $q1_supp = "SELECT * FROM loop_warehouse where id = " . $vendor_b2b_rescue;

                    db();
                    $query_supp = db_query($q1_supp);
                    while ($fetch_supp = array_shift($query_supp)) {
                        $supp_nm = get_nickname_val($fetch_supp['company_name'], $fetch_supp['b2bid']);

                        $supplier_b2bid = $fetch_supp['b2bid'];
                        $supplier_wid = $fetch_supp['id'];
                        $supplier_company_name = $fetch_supp['company_name'];
                    }
                }

                $vendor_pay = 0;
                $profit_val = 0;
                $profit_val_per = 0;
                $profit_val_str = "";
                if ($double_checked == 0) {
                    $profit_val_str = "style='color:red;'";
                }

                $to_quantity = 0;
                $dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE total > 0 and category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ";
                $dt_view_res = db_query($dt_view_qry);
                while ($dt_view_row = array_shift($dt_view_res)) {
                    $to_quantity = $dt_view_row["quantity"];
                }

                $quantity_per = ($quantity * 100) / $to_quantity;

                $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row_rs_tmprs["trans_rec_id"];

                db();
                $dt_view_res = db_query($dt_view_qry);
                while ($dt_view_row = array_shift($dt_view_res)) {
                    $vendor_pay += $dt_view_row["estimated_cost"];
                }

                $gross_profit_val = $invoice_amt - $vendor_pay;
                $profit_val = ($quantity_per * $gross_profit_val) / 100;
                $profit_val_org = $profit_val_org + str_replace(",", "", number_format(($quantity_per * $gross_profit_val) / 100, 0));

                $tot_profit = isset($tot_profit) + $profit_val;

                // $profit_val_p = 0;
                if (is_numeric($invoice_amt) && $invoice_amt != 0) {
                    $profit_val_p = ($gross_profit_val * 100) / $invoice_amt;
                }
                $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                $profit_val = "$" . number_format($profit_val, 0);

                $summtd_dealcnt = $summtd_dealcnt + 1;

                $lisoftrans .= "<tr>
							<td bgColor='#E4EAEB'>" . $initials . "</td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
							<td bgColor='#E4EAEB'>" . $quantity . "</td>
							<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
                            <td bgColor='#E4EAEB' align='right'>$" . number_format((float) str_replace(",", "", $total), 0) . "</td>
							<td bgColor='#E4EAEB' $profit_val_str align='right'>" . $profit_val . "</td>
							<td bgColor='#E4EAEB' $profit_val_str >" . $profit_val_per . "</td>
							</tr>";
            }
            $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
        }


        if ($summtd_SUMPO > 0) {

            $tot_profit = $tot_profit ?? '';
            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_profit, 0) . "</td><td bgColor='#ABC5DF'>&nbsp;</td></tr>";
        }
    }
    $lisoftrans .= "</table></span>";

    ?>
    <tr>
        <td bgColor='#ABC5DF'>PO Not Entered Yet</td>
        <td bgColor='#E4EAEB' align="right"><?php echo $summtd_SUMPO; ?></td>
        <td bgColor='#E4EAEB' align="right"><a href="#"
                onclick="load_div(<?php echo  '11' . $emprw['id']; ?>);return false;">$<?php echo number_format($summtd_dealcnt, 0); ?></a><span
                id="<?php echo  '11' . $emprw['id']; ?>" style='display:none;'><a href='#'
                    onclick='close_div(); return false;'>Close</a><?php echo  $lisoftrans; ?></span></td>
    </tr>
</table>