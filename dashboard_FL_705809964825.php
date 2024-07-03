<?php
//require ("inc/header_session.php");
?>

<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

$wid = 189;
$carrier_name = "The Finish Line";
$title_name = "The Finish Line - Dashboard";
$return_url = "dashboard_FL_705809964825";
$items = 3;

?>

<!DOCTYPE HTML>

<html>

<head>

    <title><?php echo $title_name; ?></title>




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

<body>




    <!---- TABLE TO FORMAT ----------->
    <table>
        <tr>
            <td>
                <?php
                $query = "SELECT * FROM loop_warehouse WHERE id = " . $wid;
                db();
                $res = db_query($query);
                while ($row = array_shift($res)) {
                    $warehouse_name = $row["warehouse_name"];
                ?>
                <img src="images/<?php echo $row["logo"]; ?>">
                <?php
                }
                ?>
            </td>
            <td align=center colspan="3">
                <font face="Ariel" size="5">
                    <b>UsedCardboardBoxes.com<br></b>
                    Dashboard Report for:<br>
                    <b><i><?php echo isset($warehouse_name); ?></i></b>
                    </i>
            </td>
            <td colspan="20" align="right">
                <img src="new_interface_help.gif">
            </td>
        </tr>
        <tr>
            <td width="450"></td>
        </tr>
        <tr>
            <td width="450">&nbsp;</td>
        </tr>
        <tr>
            <td valign="top" width="450">
                <!----------------------- Inventory---------------->
                <table cellSpacing="1" cellPadding="1" border="0" width="426">
                    <tr align="middle">
                        <td colspan="3" class="style7" style="height: 16px"><strong>INVENTORY</strong></td>
                    </tr>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style17">
                            <p style="text-align: center"><strong>Box Description</strong>
                        </td>
                        <td bgColor="#e4e4e4" class="style17">
                            <p align="center"><strong>Count</strong>
                        </td>
                    </tr>




                    <?php

                    $total_boxes = 0;
                    $dt_view_qry = "SELECT loop_boxes.id AS BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.warehouse_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.isbox as ISBOX FROM loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id having SUM(loop_inventory.boxgood) > 0 ORDER BY loop_warehouse.warehouse_name, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth ";
                    db();
                    $dt_view_res = db_query($dt_view_qry);

                    while ($dt_view_row = array_shift($dt_view_res)) {

                        if ($dt_view_row["A"] > 0) {

                            $dt_view_qry2 = "SELECT * FROM loop_boxes_to_warehouse WHERE loop_boxes_id LIKE '" . $dt_view_row["BID"] . "' AND loop_warehouse_id LIKE '" . $wid . "'";
                            db();
                            $dt_view_res2 = db_query($dt_view_qry2);

                            while ($dt_view_row2 = array_shift($dt_view_res2)) {

                                if ($dt_view_row["ISBOX"] != 'Y') {
                    ?>

                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style12left">
                            <?php echo $dt_view_row["C"]; ?>
                        </td>
                        <td bgColor="#e4e4e4" class="style12">
                            <?php echo number_format($dt_view_row["A"], 0); ?>
                        </td>
                    </tr>
                    <?php
                                } else {
                                ?>

                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style12left">
                            <?php echo $dt_view_row["L"] . " " . $dt_view_row["LF"] . " x " . $dt_view_row["W"] . " " . $dt_view_row["WF"] . " x " . $dt_view_row["D"] . " " . $dt_view_row["DF"]; ?>
                            <?php echo $dt_view_row["C"]; ?>
                        </td>
                        <td bgColor="#e4e4e4" class="style12">
                            <?php echo number_format($dt_view_row["A"], 0); ?>
                        </td>
                    </tr>
                    <?php
                                }
                                $total_boxes += $dt_view_row["A"];
                            }
                        }
                    } ?>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style12right"><b>TOTAL </b></td>
                        <td bgColor="#e4e4e4" class="style12"><b>
                                <?php echo number_format($total_boxes, 0); ?>
                            </b></td>
                    </tr>
                </table>
                <!--------------------- END INVENTORY ----------------------------------------------->


            </td>
            <td width="36">
                &nbsp;
            </td>
            <td valign="top" width="514">


                <!--------------- LATEST SHIPMENT TABLE ---------------->
                <table cellSpacing="1" cellPadding="1" border="0" width="488">

                    <tr align="middle">
                        <td colSpan="10" class="style7">
                            <b>VIEW LATEST SHIPMENTS</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 150" class="style17" align="center">
                            <b>Date Shipped</b>
                        </td>
                        <td style="width: 150" class="style17" align="center">
                            <b>Boxes</b>
                        </td>

                        <td align="middle" style="width: 178px" class="style16" align="center">
                            <b>View Details</b>
                        </td>
                    </tr>


                    <?php
                    $query = "SELECT SUM( loop_bol_tracking.qty ) AS A, loop_bol_tracking.bol_pickupdate AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.quantity1 as Q1, loop_bol_tracking.description1 as D1, loop_bol_tracking.quantity1 as Q2, loop_bol_tracking.description1 as D2, loop_bol_tracking.quantity1 as Q3, loop_bol_tracking.description1 as D3
FROM loop_bol_tracking 
WHERE warehouse_id = " . $wid . "
GROUP BY trans_rec_id
ORDER BY C  DESC";

                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {

                    ?>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php echo date('m-d-Y', strtotime($row["B"])); ?>
                        </td>
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php echo number_format($row["A"] + $row["Q1"] + $row["Q2"] + $row["Q3"], 0); ?>
                        </td>
                        <td bgColor="#e4e4e4" class="style3">
                            <p align="center">
                                <a href="<?php echo $return_url; ?>.php?SHIPMENT=<?php echo $row["C"] ?>">View
                                    Details</a>
                        </td>
            </td>
        </tr>
        <?php
                    }
    ?>
    </table>
    <!--------------- END SHIPMENT TABLE ---------------->
    <br>
    <?php

    if ($_REQUEST["SHIPMENT"] > 0) {
        $dt_view_qry = "SELECT * FROM loop_bol_tracking WHERE trans_rec_id = " . $_REQUEST["SHIPMENT"];
        db();
        $dt_view_res = db_query($dt_view_qry);

        $dt_view_trl_row = array_shift($dt_view_res)
    ?>
    <table cellSpacing="1" cellPadding="1" border="0" width="489">
        <tr align="middle">
            <td class="style7" colspan="2" style="height: 16px"><strong>SHIPMENT
                    DETAILS FOR <?php echo $dt_view_trl_row["bol_pickupdate"] ?></strong></td>
        </tr>
        <tr vAlign="center">
            <td bgColor="#e4e4e4" width="400" class="style17">
                <p style="text-align: center"><strong>Box Description</strong>
            </td>
            <td bgColor="#e4e4e4" class="style17" width="82">
                <p style="text-align: center"><strong>Count</strong>
            </td>
        </tr>
        <?php

            $gb = 0;
            $bb = 0;
            $gbw = 0;
            $vob = 0;


            $dt_view_qry = "SELECT * FROM loop_bol_tracking INNER JOIN loop_boxes ON loop_bol_tracking.box_id = loop_boxes.id WHERE loop_bol_tracking.trans_rec_id = " . $_REQUEST["SHIPMENT"];
            db();
            $dt_view_res = db_query($dt_view_qry);

            while ($dt_view_row = array_shift($dt_view_res)) {
                $q1 = $dt_view_row["quantity1"];
                $d1 = $dt_view_row["description1"];

                $q2 = $dt_view_row["quantity2"];
                $d2 = $dt_view_row["description2"];

                $q3 = $dt_view_row["quantity3"];
                $d3 = $dt_view_row["description3"];

                if ($dt_view_row["qty"] > 0) {
            ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12left">
                <?php echo $dt_view_row["blength"]; ?>
                <?php echo $dt_view_row["blength_frac"]; ?>
                x
                <?php echo $dt_view_row["bwidth"]; ?>
                <?php echo $dt_view_row["bwidth_frac"]; ?> x
                <?php echo $dt_view_row["bdepth"]; ?>
                <?php echo $dt_view_row["bdepth_frac"]; ?>
                <?php echo $dt_view_row["bdescription"]; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12right" width="82">
                <?php echo number_format($dt_view_row["qty"], 0); ?>
            </td>
        </tr>


        <?php
                    $gb += $dt_view_row["qty"];
                }
            }


            $dt_view_qry = "SELECT * FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $_REQUEST["SHIPMENT"];
            db();
            $dt_view_res = db_query($dt_view_qry);

            $dt_view_row = array_shift($dt_view_res);
            $q1 = $dt_view_row["quantity1"];
            $d1 = $dt_view_row["description1"];

            $q2 = $dt_view_row["quantity2"];
            $d2 = $dt_view_row["description2"];

            $q3 = $dt_view_row["quantity3"];
            $d3 = $dt_view_row["description3"];
            if ($q1 > 0) {
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12left">
                <?php echo $d1; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12right" width="82">
                <?php echo number_format($q1, 0); ?>
            </td>
        </tr>


        <?php
                $gb += $q1;
            }

            if ($q2 > 0) {
            ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12left">
                <?php echo $d2; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12right" width="82">
                <?php echo number_format($q2, 0); ?>
            </td>
        </tr>


        <?php
                $gb += $q2;
            }

            if ($q3 > 0) {
            ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12left">
                <?php echo $d3; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12right" width="82">
                <?php echo number_format($q3, 0); ?>
            </td>
        </tr>


        <?php
                $gb += $q3;
            }



            ?>

        <tr>
            <td bgColor="#e4e4e4" class="style12">&nbsp;</td>
            <td bgColor="#e4e4e4" class="style12right" width="82"><strong>
                    <?php echo number_format($gb, 0); ?>
                </strong></td>
        </tr>



    </table>


    <?php } ?>





    <!--------------- BEGIN IN PROCESS TABLE ---------------->
    <!--------------- END IN PROCESS TABLE ---------------->

    <br>


    </td>
    <td width="26">
        &nbsp;
    </td>
    <td valign="top" width="292">


        <!------------------ END PROCESSED TRAILERS -------------------->

    </td>
    </tr>
    </table>



</body>

</html>