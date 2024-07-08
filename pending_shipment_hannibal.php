<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
//require ("inc/functions.php");

?>

<table id="pending_ship_show" border="0">

    <head>
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
        </style>
    </head>

    <tr>
        <td class="style17" align="center">
            <b>ID</b>
        </td>
        <td style="width: 100" class="style17" align="center">
            <b>DATE</b>
        </td>
        <td style="width: 250" class="style17" align="center">
            <b>COMPANY</b>
        </td>
        <td class="style5" style="width: 200" align="center">
            <b>LAST NOTES LOG</b>
        </td>
        <td class="style5" style="width: 120" align="center">
            <b>LAST NOTES DATE</b>
        </td>
        <td style="width: 80" class="style17" align="center">
            <b>PICKLIST</b>
        </td>
        <td class="style5" style="width: 80" align="center">
            <b>CREATED BY</b>
        </td>
    </tr>


    <?php

    $query = "SELECT loop_transaction_buyer.so_date AS A, loop_transaction_buyer.so_employee  AS employee,  loop_warehouse.warehouse_name AS warehousename,  loop_transaction_buyer.warehouse_id AS wid ,  
loop_transaction_buyer.id AS trid, loop_transaction_buyer.picklist_print , loop_transaction_buyer.picklist_printtime  FROM loop_transaction_buyer 
INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.ignore = 0 AND 
loop_transaction_buyer.so_entered = 1  AND loop_transaction_buyer.shipped = 0 AND loop_transaction_buyer.bol_signed_uploaded = 0  AND loop_transaction_buyer.id > 67 ORDER BY trid ASC ";
    db();
    $res = db_query($query);

    while ($row = array_shift($res)) {
        $query2 = "SELECT * FROM loop_salesorders WHERE location_warehouse_id = 556 AND trans_rec_id = " . $row["trid"] . " GROUP BY trans_rec_id";
        db();
        $res2 = db_query($query2);
        $row2 = array_shift($res2);
        if ($row2["id"] > 0) {
            $sql_trans_log = "SELECT message, date FROM loop_transaction_notes WHERE loop_transaction_notes.company_id = " . $row["wid"] . " and loop_transaction_notes.rec_id = " . $row["trid"] . " ORDER BY id DESC LIMIT 0,1";
            db();
            $result_trans_log = db_query($sql_trans_log);

            $last_note_text = "";
            $last_note_date = "";
            while ($last_trans_log = array_shift($result_trans_log)) {
                $last_note_text = $last_trans_log["message"];
                $last_note_date = $last_trans_log["date"];
            }

            $query3 = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id= " . $row["trid"];
            db();
            $res3 = db_query($query3);
            $row3 = array_shift($res3);
            $date_format = date('m/d/Y', strtotime($row3["date"]));
            //echo $date_format."<br>";
            $dt = date('m/d/Y');

            //$dt = date('m/d/Y',(strtotime ('-1 day')));

            if ($date_format == $dt) {
                $bg = "#99FF99";
            } else if ($date_format < $dt) {
                $bg = "red";
            } else {
                $bg = "#e4e4e4";
            }

    ?>
    <tr vAlign="center">
        <td bgColor="<?php echo $bg; ?>" class="style3" align="center">
            <?php echo $row["trid"]; ?></td>
        <td bgColor="<?php echo $bg; ?>" class="style3" align="center">
            <?php echo $row["A"]; ?></td>
        <td bgColor="<?php echo $bg; ?>" class="style3" align="center">
            <p align="center">
                <span class="infotxt"><u><a
                            href="http://loops.usedcardboardboxes.com/search_results.php?warehouse_id=<?php echo $row["wid"] ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $row["wid"] ?>&rec_id=<?php echo $row["trid"]; ?>&display=buyer_ship"><?php echo $row["warehousename"]; ?></a></u>
                    <span style="width:570px;">
                        <table cellSpacing="1" cellPadding="1" border="0" width="570">
                            <tr align="middle">
                                <td class="style7" colspan="3" style="height: 16px"><strong>SALE ORDER DETAILS FOR ORDER
                                        ID: <?php echo $row["trid"] ?></strong></td>
                            </tr>

                            <tr vAlign="center">
                                <td bgColor="#e4e4e4" width="70" class="style17">
                                    <font size=1>
                                        <strong>QTY</strong>
                                    </font>
                                </td>
                                <td bgColor="#e4e4e4" width="100" class="style17">
                                    <font size=1>
                                        <strong>Warehouse</strong>
                                    </font>
                                </td>
                                <td bgColor="#e4e4e4" width="400" class="style17">
                                    <font size=1>
                                        <strong>Box Description</strong>
                                    </font>
                                </td>
                            </tr>
                            <?php
                                    $get_sales_order = db_query("Select *, loop_salesorders.notes AS A, loop_salesorders.pickup_date AS B, loop_salesorders.freight_vendor AS C, loop_salesorders.time AS D, loop_boxes.isbox AS I From loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " . $row["trid"]);

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
                                <td height="13" style="width: 94px" class="style1" align="right">
                                    <Font Face='arial' size='1'><?php
                                                                            $get_wh = "SELECT warehouse_name FROM loop_warehouse WHERE id = " . $boxes["location_warehouse_id"];
                                                                            db();
                                                                            $get_wh_res = db_query($get_wh);
                                                                            while ($the_wh = array_shift($get_wh_res)) {
                                                                                echo $the_wh["warehouse_name"];
                                                                            }
                                                                            ?>
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
                            </tr>
                            <?php } ?>

                            <?php
                                    $soqry = "Select * From loop_salesorders_manual WHERE trans_rec_id = " . $row["trid"];
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

                            <?php    }    ?>

                        </table>
                    </span>
                </span>
            </p>

        </td>
        <td bgColor="<?php echo $bg; ?>" class="style3" align="center">
            <?php echo $last_note_text; ?></td>
        <td bgColor="<?php echo $bg; ?>" class="style3" align="center">
            <?php echo $last_note_date; ?></td>
        <!-- Added by Mooneem 07-14-12  -->
        <td bgColor="<?php echo $bg; ?>" class="style3" align="center">
            <?php if ($row["picklist_print"] == "Y") { ?>
            <a href="#"
                onClick="PrintPickListRep('<?php echo $row["trid"] ?>' , '<?php echo $row["picklist_print"]; ?>')">Re-Print
                Picklist</a> <?php } else { ?> <a href="#"
                onClick="PrintPickListRep('<?php echo $row["trid"] ?>' , '<?php echo $row["picklist_print"]; ?>')">Print
                Picklist</a> <?php } ?>
        </td> <!-- Added by Mooneem 07-14-12  -->
        <td bgColor="<?php echo $bg; ?>" class="style3" align="center">
            <?php echo $row["employee"]; ?></td>

    </tr>

    <tr vAlign="center">
        <td bgColor="#e4e4e4" class="style3" align="center">
            <?php echo $row["trid"]; ?></td>
        <td bgColor="#e4e4e4" class="style3" colspan=6>
            <b>Freight Vendor: </b> <?php if ($row3["freight_id"] > -1) {
                                                $get_freight = db_query("Select * From loop_freightvendor WHERE id = " . $row3["freight_id"]);
                                                while ($freight = array_shift($get_freight)) {
                                                    echo $freight["company_name"];
                                                }
                                            } else echo "Vendor not yet known" ?><br>
            <b>Pickup Date: </b><?php echo $row3["date"]; ?> <br>
            <b>Pickup Time: </b><?php echo $row3["time"]; ?> <br>
            <b>Notes: </b><?php echo $row3["notes"]; ?> <br>
        </td>
    </tr>
    <?php
        }
    }
    ?>
</table>