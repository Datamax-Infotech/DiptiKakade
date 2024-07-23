<?php
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>
<script>
function checkAll() {
    var totcnt = document.getElementById('ctrltotcnt').value;

    ctrlnm = document.getElementById('chkall');
    for (var tmpcnt = 1; tmpcnt <= totcnt; tmpcnt++) {
        if (ctrlnm.checked) {
            checkboxes = document.getElementById('rec_sel' + tmpcnt);
            checkboxes.checked = true;
        } else {
            checkboxes = document.getElementById('rec_sel' + tmpcnt);
            checkboxes.checked = false;
        }
    }
}
</script>

<?php
$MGArray = array();
$sort_order = "ASC";
if ($_GET['sort_order'] == "ASC") {
    $sort_order = "DESC";
} else {
    $sort_order = "ASC";
}
?>
<form action="\qbo_sdk\exportinvoicedata_forQBO.php" method="post" name="exporttoqb">
    <table>
        <tr>
            <td class="style24" colspan=21 style="height: 16px" align="middle"><strong>Invoice to be processed in
                    QuickBook</strong></td>
        </tr>
        <tr>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>
                    <input type="checkbox" id="chkall" onClick="checkAll()" />Select</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>ID</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Company</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Last Note</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Ship to Warehouse</strong>
            </th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>PO Upload Date</strong>
            </th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Planned Delivery
                    Date</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Source</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Quantity</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Ship Date</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Last Action</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Next Action</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle"><strong>Order</strong>
            </th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle"><strong>Ship</strong>
            </th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle"><strong>Delivery</strong>
            </th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle"><strong>Pay</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle"><strong>Vendor</strong>
            </th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Invoiced Amount</strong>
            </th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Balance</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Invoice Age</strong></th>
            <th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Remove from List</strong>
            </th>
        </tr>
        <?php
        $dt_view_qry = "SELECT  loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery , loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active,
		loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , 
		loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id 
		left join loop_bol_files on loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 
		and inv_entered = 0 and loop_transaction_buyer.ignore = 0   and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id in (select trans_rec_id from loop_invoice_details) 
		GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id limit 5";
        db();
        $dt_view_res = db_query($dt_view_qry);
        while ($dt_view_row = array_shift($dt_view_res)) {        //This is the payment Info for the Customer paying UCB

            $activeflg_str = "";
            if ($dt_view_row["Active"] == 0) {
                $activeflg_str = "<font face='arial' size='2' color='red'><b>&nbsp;INACTIVE</b><font>";
            }

            $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
            db();
            $payment_qry = db_query($payments_sql);
            $payment = array_shift($payment_qry);

            //This is the payment info for UCB paying the related vendors
            $vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
            db();
            $vendor_qry = db_query($vendor_sql);
            $vendor = array_shift($vendor_qry);

            //Info about Shipment
            $bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $dt_view_row["I"] . "' ORDER BY id DESC";
            db();
            $bol_file_res = db_query($bol_file_qry);
            $bol_file_row = array_shift($bol_file_res);

            $fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $dt_view_row["I"];
            db();
            $fbookresult = db_query($fbooksql);
            $freightbooking = array_shift($fbookresult);


            //Last tansaction Note
            $sql_ln = "SELECT * FROM loop_transaction_notes WHERE loop_transaction_notes.company_id = " . $dt_view_row["D"] . " and loop_transaction_notes.rec_id = " . $dt_view_row["I"] . " ORDER BY id DESC LIMIT 0,1";
            db();
            $result_ln = db_query($sql_ln);
            $last_note = array_shift($result_ln);


            $last_note_text = $last_note["message"];
            $last_note_date = $last_note["date"];

            /*if ($dt_view_row["po_delivery_dt"] <> "") {
				$Planned_delivery_date = $dt_view_row["po_delivery"]." ". date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
			}else {
				$Planned_delivery_date = $dt_view_row["po_delivery"];
			}*/

            if ($dt_view_row["po_delivery_dt"] == "") {
                $Planned_delivery_date = $dt_view_row["po_delivery"];
            } else {
                $Planned_delivery_date = date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
            }

            $vendors_paid = 0; //Are the vendors paid
            $vendors_entered = 0; //Has a vendor transaction been entered?
            $invoice_paid = 0; //Have they paid their invoice?
            $invoice_entered = 0; //Has the inovice been entered
            $signed_customer_bol = 0;     //Customer Signed BOL Uploaded
            $courtesy_followup = 0;     //Courtesy Follow Up Made
            $delivered = 0;     //Delivered
            $signed_driver_bol = 0;     //BOL Signed By Driver
            $shipped = 0;     //Shipped
            $bol_received = 0;     //BOL Received @ WH
            $bol_sent = 0;     //BOL Sent to WH"
            $bol_created = 0;     //BOL Created
            $freight_booked = 0; //freight booked
            $sales_order = 0;   // Sales Order entered
            $po_uploaded = 0;  //po uploaded 

            //Are all the vendors paid?
            if ($vendor["B"] == 2 && $vendor["C"] == 2) {
                $vendors_paid = 1;
            }

            //Have we entered a vendor transaction?
            if ($vendor["A"] > 0) {
                $vendors_entered = 1;
            }

            //Have they paid their invoice?
            if (number_format($dt_view_row["F"], 2) == number_format($payment["A"], 2) && $dt_view_row["F"] != "") {
                $invoice_paid = 1;
            }
            if ($dt_view_row["no_invoice"] == 1) {
                $invoice_paid = 1;
            }

            //Has an invoice amount been entered?
            if ($dt_view_row["F"] > 0) {
                $invoice_entered = 1;
            }
            if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") {
                $signed_customer_bol = 1;
            }    //Customer Signed BOL Uploaded
            if ($bol_file_row["bol_shipment_followup"] > 0) {
                $courtesy_followup = 1;
            }    //Courtesy Follow Up Made
            if ($bol_file_row["bol_shipment_received"] > 0) {
                $delivered = 1;
            }    //Delivered
            if ($bol_file_row["bol_signed_file_name"] != "") {
                $signed_driver_bol = 1;
            }    //BOL Signed By Driver
            if ($bol_file_row["bol_shipped"] > 0) {
                $shipped = 1;
            }    //Shipped
            if ($bol_file_row["bol_received"] > 0) {
                $bol_received = 1;
            }    //BOL Received @ WH
            if ($bol_file_row["bol_sent"] > 0) {
                $bol_sent = 1;
            }    //BOL Sent to WH"
            if ($bol_file_row["id"] > 0) {
                $bol_created = 1;
            }    //BOL Created

            if ($freightbooking["id"] > 0) {
                $freight_booked = 1;
            } //freight booked

            if (($dt_view_row["G"] == 1)) {
                $sales_order = 1;
            } //sales order created
            if ($dt_view_row["H"] != "") {
                $po_uploaded = 1;
            } //po uploaded 

            $boxsource = "";
            $box_qry = "SELECT loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.typeid = 1 AND loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
            db();
            $box_res = db_query($box_qry);
            while ($box_row = array_shift($box_res)) {
                $boxsource = $box_row["C"];
            }
            //echo $box_qry;

            //if ($shipped == 1 && $invoice_entered == 0)			if ($shipped == 1)
            {

                $dt_view_qry2 = "SELECT SUM(loop_bol_tracking.qty) AS A, loop_bol_tracking.bol_STL1 AS B, loop_bol_tracking.location_warehouse_id , loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.warehouse_id AS D, loop_bol_tracking.bol_pickupdate AS E, loop_bol_tracking.quantity1 AS Q1, loop_bol_tracking.quantity2 AS Q2, loop_bol_tracking.quantity3 AS Q3 FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $dt_view_row["I"];
                db();
                $dt_view_res2 = db_query($dt_view_qry2);
                $dt_view_row2 = array_shift($dt_view_res2);

                $sBolShipto = "";
                $qry = "SELECT company_name FROM loop_warehouse WHERE id = " . $dt_view_row2["location_warehouse_id"];
                db();
                $res = db_query($qry);
                while ($row = array_shift($res)) {
                    $sBolShipto = $row["company_name"];
                }

                $dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
                db();
                $dt_view_res3 = db_query($dt_view_qry3);
                $dt_view_row3 = array_shift($dt_view_res3);
                $balance = number_format(($dt_view_row["F"] - $dt_view_row3["PAID"]), 2);

                $start_t = strtotime($dt_view_row["J"]);
                $end_time =  strtotime('now');
                $invoice_age = number_format(($end_time - $start_t) / (3600 * 24), 0);

                if ($invoice_paid == 1) {
                    if ($vendors_paid == 1) {
                        $last_action_str = "Vendors Paid";
                    } elseif ($vendors_entered == 1) {
                        $last_action_str = "Vendors Invoiced";
                    } else {
                        $last_action_str = "Customer Paid";
                    }
                } elseif ($invoice_entered == 1) {
                    $last_action_str = "Customer Invoiced";
                } elseif ($signed_customer_bol == 1) {
                    $last_action_str = "Customer Signed BOL";
                } elseif ($courtesy_followup == 1) {
                    $last_action_str = "Courtesy Followup Made";
                } elseif ($delivered == 1) {
                    $last_action_str = "Delivered";
                } elseif ($signed_driver_bol == 1) {
                    $last_action_str = "Shipped - Driver Signed";
                } elseif ($shipped == 1) {
                    $last_action_str = "Shipped";
                } elseif ($bol_received == 1) {
                    $last_action_str = "BOL @ Warehouse";
                } elseif ($bol_sent == 1) {
                    $last_action_str = "BOL Sent to Warehouse";
                } elseif ($bol_created == 1) {
                    $last_action_str = "BOL Created";
                } elseif ($freight_booked == 1) {
                    $last_action_str = "Freight Booked";
                } elseif ($sales_order == 1) {
                    $last_action_str = "Sales Order Entered";
                } elseif ($po_uploaded == 1) {
                    $last_action_str = "PO Uploaded";
                }


                if ($invoice_paid == 1) {
                    if ($vendors_paid == 1) {
                        $next_action_str = "Complete";
                    } elseif ($vendors_entered == 1) {
                        $next_action_str = "Pay Vendor";
                    } else {
                        $next_action_str = "Enter Vendor Invoices";
                    }
                } elseif ($invoice_entered == 1) {
                    $next_action_str = "Customer to Pay";
                } elseif ($signed_customer_bol == 1) {
                    $next_action_str = "Invoice Customer";
                } elseif ($courtesy_followup == 1) {
                    $next_action_str = "Invoice Customer";
                } elseif ($delivered == 1) {
                    $next_action_str = "Send Courtesy Folllow-up";
                } elseif ($signed_driver_bol == 1) {
                    $next_action_str = "Confirm Delivery";
                } elseif ($shipped == 1) {
                    $next_action_str = "Upload Signed BOL";
                } elseif ($bol_received == 1) {
                    $next_action_str = "Ready to Ship";
                } elseif ($bol_sent == 1) {
                    $next_action_str = "Confirm BOL Receipt @ Warehouse";
                } elseif ($bol_created == 1) {
                    $next_action_str = "Send BOL to Warehouse";
                } elseif ($freight_booked == 1) {
                    $next_action_str = "Create BOL";
                } elseif ($sales_order == 1) {
                    $next_action_str = "Book Freight";
                } elseif ($po_uploaded == 1) {
                    $next_action_str = "Enter Sales Order";
                }
            }    //if not paid

            $sort_warehouse_id = $dt_view_row["D"];
            $sort_id = $dt_view_row["I"];
            $sort_company_name = $dt_view_row["B"];
            $sort_last_note = strtolower($last_note["message"]);
            $sort_last_note_dt = $last_note["date"];
            $sort_po_delivery_dt = $Planned_delivery_date;
            $sort_source = strtolower($boxsource);
            $sort_quantity =  ($dt_view_row2["A"] + $dt_view_row2["Q1"] + $dt_view_row2["Q2"] + $dt_view_row2["Q3"]);
            $sort_ship_date = $dt_view_row2["E"];
            $sort_last_action = $last_action_str;
            $sort_next_action = $next_action_str;
            $sort_invoice_amount = number_format($dt_view_row["F"], 2);
            $sort_balance = $balance;
            $sort_invoice_age = $invoice_age;
            $sort_flag = $activeflg_str;
            $sort_b2bid = $dt_view_row["b2bid"];

            $MGArray[] = array(
                'warehouse_id' => $sort_warehouse_id, 'ID' => $sort_id, 'company_name' => $sort_company_name, 'last_note_text' => $sort_last_note, 'po_upload_date' => $sort_last_note_dt, 'po_delivery_dt' => $sort_po_delivery_dt, 'source' => $sort_source, 'quantity' => $sort_quantity, 'ship_date' => $sort_ship_date, 'last_action' => $sort_last_action, 'next_action' => $sort_next_action, 'invoice_amount' => $sort_invoice_amount, 'balance' => $sort_balance, 'invoice_age' => $sort_invoice_age, 'active' => $sort_flag,
                'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'shipped' => $shipped, 'bol_created' => $bol_created, 'courtesy_followup' => $courtesy_followup,
                'delivered' => $delivered, 'invoice_paid' => $invoice_paid, 'invoice_entered' => $invoice_entered, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered, 'b2bid' => $sort_b2bid, 'BolShipto' => $sBolShipto
            );
        }


        if ($_GET['sort'] == "ID" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_I = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_I[] = $MGArraytmp['ID'];
            }
            array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
        }
        if ($_GET['sort'] == "ID" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_I = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_I[] = $MGArraytmp['ID'];
            }
            array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
        //////
        if ($_GET['sort'] == "company_name" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_B = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_B[] = $MGArraytmp['company_name'];
            }
            array_multisort($MGArraysort_B, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "company_name" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_B = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_B[] = $MGArraytmp['company_name'];
            }
            array_multisort($MGArraysort_B, SORT_DESC, SORT_STRING, $MGArray);
        }

        //////////
        if ($_GET['sort'] == "last_note_text" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_C = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_C[] = $MGArraytmp['last_note_text'];
            }
            array_multisort($MGArraysort_C, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "last_note_text" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_C = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_C[] = $MGArraytmp['last_note_text'];
            }

            array_multisort($MGArraysort_C, SORT_DESC, SORT_STRING, $MGArray);
        }

        ///////
        if ($_GET['sort'] == "po_upload_date" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_D = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_D[] = $MGArraytmp['po_upload_date'];
            }
            array_multisort($MGArraysort_D, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "po_upload_date" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_D = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_D[] = $MGArraytmp['po_upload_date'];
            }
            array_multisort($MGArraysort_D, SORT_DESC, SORT_STRING, $MGArray);
        }
        ///////
        if ($_GET['sort'] == "po_delivery_dt" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_E = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_E[] = $MGArraytmp['po_delivery_dt'];
            }
            array_multisort($MGArraysort_E, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "po_delivery_dt" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_E = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_E[] = $MGArraytmp['po_delivery_dt'];
            }
            array_multisort($MGArraysort_E, SORT_DESC, SORT_STRING, $MGArray);
        }
        /////////
        if ($_GET['sort'] == "source" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_F = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_F[] = $MGArraytmp['source'];
            }
            array_multisort($MGArraysort_F, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "source" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_F = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_F[] = $MGArraytmp['source'];
            }
            array_multisort($MGArraysort_F, SORT_DESC, SORT_STRING, $MGArray);
        }
        ////////		
        if ($_GET['sort'] == "quantity" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_G = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_G[] = $MGArraytmp['quantity'];
            }
            array_multisort($MGArraysort_G, SORT_ASC, SORT_NUMERIC, $MGArray);
        }
        if ($_GET['sort'] == "quantity" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_G = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_G[] = $MGArraytmp['quantity'];
            }
            array_multisort($MGArraysort_G, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
        //////////
        if ($_GET['sort'] == "ship_date" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_H = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_H[] = $MGArraytmp['ship_date'];
            }
            array_multisort($MGArraysort_H, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "ship_date" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_H = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_H[] = $MGArraytmp['ship_date'];
            }
            array_multisort($MGArraysort_H, SORT_DESC, SORT_STRING, $MGArray);
        }
        //////////			
        if ($_GET['sort'] == "last_action" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_J = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_J[] = $MGArraytmp['last_action'];
            }
            array_multisort($MGArraysort_J, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "last_action" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_J = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_J[] = $MGArraytmp['last_action'];
            }
            array_multisort($MGArraysort_J, SORT_DESC, SORT_STRING, $MGArray);
        }
        ///////////
        if ($_GET['sort'] == "next_action" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_K = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_K[] = $MGArraytmp['next_action'];
            }
            array_multisort($MGArraysort_K, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "next_action" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_K = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_K[] = $MGArraytmp['next_action'];
            }
            array_multisort($MGArraysort_K, SORT_DESC, SORT_STRING, $MGArray);
        }
        ///////
        if ($_GET['sort'] == "invoice_amount" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_L = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_L[] = $MGArraytmp['invoice_amount'];
            }
            array_multisort($MGArraysort_L, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "invoice_amount" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_L = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_L[] = $MGArraytmp['invoice_amount'];
            }
            array_multisort($MGArraysort_L, SORT_DESC, SORT_STRING, $MGArray);
        }
        ////////
        if ($_GET['sort'] == "balance" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_M = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_M[] = $MGArraytmp['balance'];
            }
            array_multisort($MGArraysort_M, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "balance" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_M = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_M[] = $MGArraytmp['balance'];
            }
            array_multisort($MGArraysort_M, SORT_DESC, SORT_STRING, $MGArray);
        }
        ///////
        if ($_GET['sort'] == "invoice_age" && $_GET['sort_order'] == "ASC") {
            $MGArraysort_N = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_N[] = $MGArraytmp['invoice_age'];
            }
            array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);
        }
        if ($_GET['sort'] == "invoice_age" && $_GET['sort_order'] == "DESC") {
            $MGArraysort_N = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_N[] = $MGArraytmp['invoice_age'];
            }
            array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);
        }

        $MGArraysort_warehouse_id = array();

        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_warehouse_id[] = $MGArraytmp['warehouse_id'];
        }

        $MGArraysort_active = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_MGArraysort_active[] = $MGArraytmp['active'];
        }

        $MGArraysort_sales_order = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_sales_order[] = $MGArraytmp['sales_order'];
        }

        $MGArraysort_po_uploaded = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_po_uploaded[] = $MGArraytmp['po_uploaded'];
        }
        $MGArraysort_shipped = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_shipped[] = $MGArraytmp['shipped'];
        }
        $MGArraysort_bol_created = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_bol_created[] = $MGArraytmp['bol_created'];
        }
        $MGArraysort_courtesy_followup = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_courtesy_followup[] = $MGArraytmp['courtesy_followup'];
        }
        $MGArraysort_delivered = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_delivered[] = $MGArraytmp['delivered'];
        }
        $MGArraysort_invoice_paid = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_invoice_paid[] = $MGArraytmp['invoice_paid'];
        }
        $MGArraysort_invoice_entered = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_invoice_entered[] = $MGArraytmp['invoice_entered'];
        }
        $MGArraysort_vendors_paid = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_vendors_paid[] = $MGArraytmp['vendors_paid'];
        }
        $MGArraysort_vendors_entered = array();
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_vendors_entered[] = $MGArraytmp['vendors_entered'];
        }

        $ctrl = 0;
        foreach ($MGArray as $MGArraytmp2) {
            $ctrl = $ctrl + 1;
        ?>

        <tr>

            <td bgColor="#e4e4e4" class="style12">
                <input type="checkbox" id="rec_sel<?php echo $ctrl; ?>" name="rec_sel[]" checked
                    value="<?php echo $MGArraytmp2['ID'] . "|" . $MGArraytmp2['b2bid']; ?>" />

            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['ID']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <p align="center">
                    <u><a
                            href="http://loops.usedcardboardboxes.com/search_results.php?ID=<?php echo $MGArraytmp2['warehouse_id']; ?>&show=companyinfo&proc=View&searchcrit=&rec_type=Supplier&page=0"><?php echo $MGArraytmp2["company_name"] . $MGArraytmp2["active"]; ?></a></u>
                </p>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo ($MGArraytmp2['last_note_text']); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo ($MGArraytmp2['BolShipto']); ?>
            </td>

            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['po_upload_date']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['po_delivery_dt']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo ucfirst($MGArraytmp2['source']); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['quantity']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['ship_date']; ?>
            </td>

            <!---- Last Action ------->
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2["last_action"]; ?>
            </td>

            <!---- Next Action ------->
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2["next_action"]; ?>
            </td>

            <?php

                $open = "<img src=\"images/circle_open.gif\" border=\"0\">";
                $half = "<img src=\"images/circle__partial.gif\" border=\"0\">";
                $full = "<img src=\"images/complete.jpg\" border=\"0\">";

                ?>

            <!------------- ORDERED ---------->
            <td bgColor="#e4e4e4" class="style12" align="center">

                <a
                    href="http://loops.usedcardboardboxes.com/search_results.php?ID=<?php echo $MGArraytmp2['warehouse_id']; ?>&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_view">
                    <?php
                        if ($MGArraytmp2['sales_order'] == 1) {
                            echo $full;
                        } elseif ($MGArraytmp2['po_uploaded'] == 1) {
                            echo $half;
                        } else {
                            echo $open;
                        } ?>
                </a>
            </td>

            <!------------- SHIPPED ---------->

            <td bgColor="#e4e4e4" class="style12" align="center">
                <a
                    href="http://loops.usedcardboardboxes.com/search_results.php?ID=<?php echo $MGArraytmp2['warehouse_id']; ?>&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_ship">
                    <?php

                        if ($MGArraytmp2['shipped'] == 1) {
                            echo $full;
                        } elseif ($MGArraytmp2['bol_created'] == 1) {
                            echo $half;
                        } else {
                            echo $open;
                        } ?></a>
            </td>

            <!------------- RECEIVED ---------->
            <td bgColor="#e4e4e4" class="style12" align="center">
                <a
                    href="http://loops.usedcardboardboxes.com/search_results.php?ID=<?php echo $MGArraytmp2['warehouse_id']; ?>&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_received">
                    <?php
                        if ($MGArraytmp2['courtesy_followup'] == 1) {
                            echo $full;
                        } elseif ($MGArraytmp2['delivered'] == 1) {
                            echo $half;
                        } else {
                            echo $open;
                        }

                        ?></a>
            </td>

            <!------------- PAY ---------->
            <td bgColor="#e4e4e4" class="style12" align="center">
                <center>
                    <a
                        href="http://loops.usedcardboardboxes.com/search_results.php?ID=<?php echo $MGArraytmp2['warehouse_id']; ?>&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_payment">
                        <?php

                            if ($MGArraytmp2['invoice_paid'] == 1) {
                                echo $full;
                            } elseif ($MGArraytmp2['invoice_entered'] == 1) {
                                echo $half;
                            } else {
                                echo $open;
                            } ?></a>
                </center>
            </td>

            <!------------- VENDOR ---------->
            <td bgColor="#e4e4e4" class="style12" align="center">
                <center>
                    <a
                        href="http://loops.usedcardboardboxes.com/search_results.php?ID=<?php echo $MGArraytmp2['warehouse_id']; ?>&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_invoice">
                        <?php

                            if ($MGArraytmp2['vendors_paid'] == 1) {
                                echo $full;
                            } elseif ($MGArraytmp2['vendors_entered'] == 1) {
                                echo $half;
                            } else {
                                echo $open;
                            } ?></a>
                </center>
            </td>
            </td>

            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2["invoice_amount"]; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2["balance"]; ?>
            </td>
            <?php

                if ($MGArraytmp2["invoice_age"] > 30 && $MGArraytmp2["invoice_age"] < 1000) {
                ?>
            <td bgColor="#ff0000" class="style12">
                <?php echo $MGArraytmp2["invoice_age"]; ?>
            </td>

            <?php
                } elseif (number_format(($end_time - $start_t) / (3600 * 24000), 0) > 10) {
                ?>
            <td bgColor="#e4e4e4" class="style12">
                &nbsp;
            </td>

            <?php
                } else {
                ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2["invoice_age"]; ?>
            </td>
            <?php
                }
                ?>
            <td bgColor="#e4e4e4" class="style12">
                <input style="cursor:pointer;" type=button
                    onclick="confirmationIgnore('<?php echo $MGArraytmp2["company_name"]; ?>','<?php echo $MGArraytmp2['ID']; ?>')"
                    value="X">
            </td>
        </tr>
        <?php
        }
        ?>

    </table>
    <br><br>

    <input type="hidden" value="<?php echo $ctrl; ?>" name="ctrltotcnt" id="ctrltotcnt" />
    <input type="submit" value="Generate the QB output file" />
</form>