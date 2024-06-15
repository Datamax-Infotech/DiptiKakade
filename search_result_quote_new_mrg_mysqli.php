<br /><br />
<?php

$zipStr = "";
$zipShipStr = "";


if ($_REQUEST["display_b2b"] == "quote") {
?>
<!------------------------------------
--------------------------------------
GAYLORD INFO
--------------------------------------
------------------------------------->
<?php

    $x = "Select * from companyInfo Where ID = " . isset($b2bid);

    db_b2b();
    $dt_view_res = db_query($x);
    $row = array_shift($dt_view_res);

    $aa = "Select * from boxesGaylord Where companyID = " . isset($b2bid);

    db_b2b();
    $dt_view_res = db_query($aa);
    while ($gb = array_shift($dt_view_res)) {

        if ($_REQUEST["Edit"] != "50")
            //in tableFunctions.asp. This is the normal view of the Gaylord Box info.
            viewGaylordInfo($gb["shape"], $gb["shape_rect"], $gb["shape_oct"], $gb["wall"], $gb["wall_2"], $gb["wall_3"], $gb["wall_4"], $gb["wall_5"], $gb["thetop"], $gb["top_nolid"], $gb["top_partial"], $gb["top_full"], $gb["top_hinged"], $gb["top_remove"], $gb["bottom"], $gb["bottom_no"], $gb["bottom_partial"], $gb["bottom_partialsheet"], $gb["bottom_fullflap"], $gb["bottom_interlocking"], $gb["bottom_tray"], $gb["vents"], $gb["vents_no"], $gb["vents_yes"], $gb["box_pallet"], $gb["box_condition"], $gb["quantity"], $gb["frequency"], $gb["previous_contents"], $gb["largest_qty"], $gb["loading"], $gb["price_beat"], $gb["delivery_date"], isset($b2bid), "yes");
        else
            //in tableFunctions.asp. This is the editable view. Results go to editTables.asp
            viewGaylordEdit($gb["shape"], $gb["shape_rect"], $gb["shape_oct"], $gb["wall"], $gb["wall_2"], $gb["wall_3"], $gb["wall_4"], $gb["wall_5"], $gb["thetop"], $gb["top_nolid"], $gb["top_partial"], $gb["top_full"], $gb["top_hinged"], $gb["top_remove"], $gb["bottom"], $gb["bottom_no"], $gb["bottom_partial"], $gb["bottom_partialsheet"], $gb["bottom_fullflap"], $gb["bottom_interlocking"], $gb["bottom_tray"], $gb["vents"], $gb["vents_no"], $gb["vents_yes"], $gb["box_pallet"], $gb["box_condition"], $gb["quantity"], $gb["frequency"], $gb["previous_contents"], $gb["largest_qty"], $gb["loading"], $gb["price_beat"], $gb["delivery_date"], isset($b2bid));

        echo "<br>";
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Gaylord Match

    if (function_exists('remove_non_numeric')) {

        $zipShipStr = "Select * from ZipCodes WHERE zip = " . remove_non_numeric($row["shipZip"]);
    }


    db_b2b();
    $dt_view_res = db_query($zipShipStr);
    while ($zip = array_shift($dt_view_res)) {
        $shipLat = $zip["latitude"];

        $shipLong = $zip["longitude"];
    }


    $aa = "Select * from boxesGaylord Where companyID = " . isset($b2bid);
    // Added by Mooneem Jul-13-12 to Bring the green thread at top	

    $MGArray = array();

    ?>
<br>
<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4">
    <tr align="center">
        <td colspan="2" bgcolor="#C0CDDA">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">MATCHING GAYLORDS</font>
        </td>
    </tr>

    <?php
        // Added by Mooneem Jul-13-12 to Bring the green thread at top	

        db_b2b();
        $dt_view_res = db_query($aa);
        while ($gb = array_shift($dt_view_res)) {
            $dk = "Select *, inventory.id AS I from inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.gaylord LIKE '1' AND active LIKE 'A' ORDER BY vendors.Name";



            db_b2b();
            $yyyy = db_query($dk);
            $xxx =  tep_db_num_rows($yyyy);

        ?>

    <?php

            while ($inv = array_shift($yyyy)) {
                $count = 0;
                //echo $count;
                $tipStr = "Dimensions: ";

                if ((int) $gb["shape_rect"] + (int) $inv["shape_rect"] == 2) {
                    $count = $count + 1;
                }

                if ((int) $gb["shape_oct"] + (int) $inv["shape_oct"] == 2)
                    $count = $count + 1;

                if ((int) $gb["wall_2"] + (int) $inv["wall_2"] == 2)
                    $count = $count + 1;

                if ((int) $gb["wall_3"] + (int) $inv["wall_3"] == 2)
                    $count = $count + 1;

                if ((int) $gb["wall_4"] + (int) $inv["wall_4"] == 2)
                    $count = $count + 1;

                if ((int) $gb["wall_5"] + (int) $inv["wall_5"] == 2)
                    $count = $count + 1;

                if ((int) $gb["top_nolid"] + (int) $inv["top_nolid"] == 2)
                    $count = $count + 1;

                if ((int) $gb["top_partial"] + (int) $inv["top_partial"] == 2)
                    $count = $count + 1;

                if ((int) $gb["top_full"] + (int) $inv["top_full"] == 2)
                    $count = $count + 1;

                if ((int) $gb["top_hinged"] + (int) $inv["top_hinged"] == 2)
                    $count = $count + 1;

                if ((int) $gb["top_remove"] + (int) $inv["top_remove"] == 2)
                    $count = $count + 1;

                if ((int) $gb["bottom_no"] + (int) $inv["bottom_no"] == 2)
                    $count = $count + 1;

                if ((int) $gb["bottom_partial"] + (int) $inv["bottom_partial"] == 2)
                    $count = $count + 1;

                if ((int) $gb["bottom_partialsheet"] + (int) $inv["bottom_partialsheet"] == 2)
                    $count = $count + 1;

                if ((int) $gb["bottom_fullflap"] + (int) $inv["bottom_fullflap"] == 2)
                    $count = $count + 1;

                if ((int) $gb["bottom_interlocking"] + (int) $inv["bottom_interlocking"] == 2)
                    $count = $count + 1;

                if ((int) $gb["bottom_tray"] + (int) $inv["bottom_tray"] == 2)
                    $count = $count + 1;

                if ((int) $gb["vents_no"] + (int) $inv["vents_no"] == 2)
                    $count = $count + 1;

                if ((int) $gb["vents_yes"] + (int) $inv["vents_yes"] == 2)
                    $count = $count + 1;

                if ($count >= 4) {

            ?>


    <?php

                    // Added by Mooneem Jul-13-12 to Bring the green thread at top	
                    $tmpTDstr = "<a href='http://b2b.usedcardboardboxes.com/b2b5/updateInventoryItem.asp?InventoryID=" . $inv["I"] . "' ";

                    if ($inv["lengthInch"] != "")
                        $tipStr = $tipStr . $inv["lengthInch"];

                    if ($inv["lengthFraction"] != "")
                        $tipStr = $tipStr . " " . $inv["lengthFraction"];

                    $tipStr = $tipStr . " x ";

                    if ($inv["widthInch"] != "")
                        $tipStr = $tipStr . $inv["widthInch"];

                    if ($inv["widthFraction"] != "")
                        $tipStr = $tipStr . " " . $inv["widthFraction"];

                    $tipStr = $tipStr . " x ";

                    if ($inv["depthInch"] != "")
                        $tipStr = $tipStr . $inv["depthInch"];

                    if ($inv["depthFraction"] != "")
                        $tipStr = $tipStr . " " . $inv["depthFraction"];

                    $tipStr = $tipStr . "<BR>";

                    if ($inv["description"] != "")
                        $tipStr = $tipStr . "Description: " . $inv["description"] . "<br>";

                    if ($inv["cubicFeet"] != "")
                        $tipStr = $tipStr . "Cubic Feet: " . $inv["cubicFeet"] . "<br>";

                    if ($inv["newUsed"] != "")
                        $tipStr = $tipStr . "Condition: " . $inv["newUsed"] . "<br>";

                    if ($inv["printing"] != "")
                        $tipStr = $tipStr . "Printing: " . $inv["printing"] . "<br>";

                    if ($inv["labels"] != "")
                        $tipStr = $tipStr . "Labels: " . $inv["labels"] . "<br>";

                    if ($inv["writing"] != "")
                        $tipStr = $tipStr . "Writing: " . $inv["writing"] . "<br>";

                    if ($inv["burst"] != "")
                        $tipStr = $tipStr . "Burst: " . $inv["burst"] . "<br>";

                    if ($inv["sales_price"] != "")
                        $tipStr = $tipStr . "Sales Price: $" . $inv["sales_price"] . "<BR>";

                    if ($inv["ulineDollar"] != "")
                        $tipStr = $tipStr . "Min FOB Price: $" .  number_format($inv["ulineDollar"] + (((int) (100 * $inv["ulineCents"])) / 100), 2) . "<BR>";
                    if ($inv["costDollar"] != "")
                        $tipStr = $tipStr . "Cost: $" . number_format($inv["costDollar"] + (((int) (100 * $inv["costCents"])) / 100), 2) . "<BR>";

                    if ($inv["quantity"] != 0)
                        $tipStr = $tipStr . "Quantity: " . $inv["quantity"] . "<br>";

                    if ($inv["quantity_per_pallet"] != "")
                        $tipStr = $tipStr . "Quantity/Pallet: " . $inv["quantity_per_pallet"] . "<br>";

                    if ($inv["weight_per_pallet"] != "")
                        $tipStr = $tipStr . "Weight/Pallet: " . $inv["weight_per_pallet"] . "<br>";

                    if ($inv["vendor"] != "")
                        $tipStr = $tipStr . "Vendor: " . $inv["Name"] . "<br>";

                    if ($inv["location"] != "")
                        $tipStr = $tipStr . "Location: " . $inv["location"] . "<br>";


                    if ($inv["shape_rect"] == "1")
                        $tipStr = $tipStr . "Shape: Rectangular<BR>";

                    if ($inv["shape_oct"] == "1")
                        $tipStr = $tipStr . "Shape: Octagonal<BR>";

                    if ($inv["wall_2"] == "1")
                        $tipStr = $tipStr . "Wall: Double<BR>";

                    if ($inv["wall_3"] == "1")
                        $tipStr = $tipStr . "Wall: Triple<BR>";

                    if ($inv["wall_4"] == "1")
                        $tipStr = $tipStr . "Wall: Quad(4)<BR>";

                    if ($inv["wall_5"] == "1")
                        $tipStr = $tipStr . "Wall: Five<BR>";

                    if ($inv["top_nolid"] == "1")
                        $tipStr = $tipStr . "Top: None<BR>";

                    if ($inv["top_partial"] == "1")
                        $tipStr = $tipStr . "Top: Partial<BR>";

                    if ($inv["top_full"] == "1")
                        $tipStr = $tipStr . "Top: Full Flaps<BR>";

                    if ($inv["top_hinged"] == "1")
                        $tipStr = $tipStr . "Top: Hinged Lid<BR>";

                    if ($inv["top_remove"] == "1")
                        $tipStr = $tipStr . "Top: Removable Lid<BR>";

                    if ($inv["bottom_no"] == "1")
                        $tipStr = $tipStr . "Bottom: None<BR>";

                    if ($inv["bottom_partial"] == "1")
                        $tipStr = $tipStr . "Bottom: Partial<BR>";

                    if ($inv["bottom_partialsheet"] == "1")
                        $tipStr = $tipStr . "Bottom: Partial w/ Slip Sheet<BR>";

                    if ($inv["bottom_fullflap"] == "1")
                        $tipStr = $tipStr . "Bottom: Full Flap<BR>";

                    if ($inv["bottom_interlocking"] == "1")
                        $tipStr = $tipStr . "Bottom: Interlocking Flaps<BR>";

                    if ($inv["bottom_tray"] == "1")
                        $tipStr = $tipStr . "Bottom: Tray<BR>";

                    if ($inv["vents_no"] == "1")
                        $tipStr = $tipStr . "Vents: None<BR>";

                    if ($inv["vents_yes"] == "1")
                        $tipStr = $tipStr . "Vents: Vent Holes<BR>";
                    //		echo $tipStr;
                    if (function_exists('remove_non_numeric')) {

                        $zipStr = "Select * from ZipCodes WHERE zip = " . remove_non_numeric($inv["location"]);
                    }

                    if (remove_non_numeric($inv["location"]) != "") {

                        db_b2b();
                        $dt_view_res3 = db_query($zipStr);
                        while ($ziploc = array_shift($dt_view_res3)) {
                            $locLat = $ziploc["latitude"];

                            $locLong = $ziploc["longitude"];
                        }

                        //	echo $locLong;

                        $distLat = (isset($shipLat) - isset($locLat)) * 3.141592653 / 180;
                        $distLong = (isset($shipLong) - isset($locLong)) * 3.141592653 / 180;

                        $distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos(isset($shipLat) * 3.14159 / 180) * Cos(isset($locLat) * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
                        //		echo $distA . "p"; 
                        $distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
                        //			echo $distC . "g";
                        $tipStr = $tipStr . "Distance: " . (int) (6371 * $distC * .621371192) . " miles<BR>";

                        // Added by Mooneem Jul-13-12 to Bring the green thread at top	
                        $tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . $tipStr . "')\" onmouseout=\"UnTip()\"";

                        //echo " >" ;
                        $tmpTDstr =  $tmpTDstr . " >";

                        $tmpTDstr =  $tmpTDstr . "<strong><font color=black>";
                        if ((int) (6371 * $distC * .621371192) < 301) {    //echo "<strong><font color=green>";
                            $tmpTDstr =  $tmpTDstr . "<strong><font color=green>";
                        }
                        if (((int) (6371 * $distC * .621371192) < 601) && ((int) (6371 * $distC * .621371192) > 300)) {    //echo "<strong><font color='#FF9933'>";
                            $tmpTDstr =  $tmpTDstr . "<strong><font color='#FF9933'>";
                        }
                        if (((int) (6371 * $distC * .621371192) < 1001) && ((int) (6371 * $distC * .621371192) > 600)) {    //echo "<strong><font color=red>";
                            $tmpTDstr =  $tmpTDstr . "<strong><font color=red>";
                        }



                        $tmpTDstr =  $tmpTDstr . $inv["lengthInch"] . " x " . $inv["widthInch"] . " x " . $inv["depthInch"] . " " . $inv["description"] . " - " . $inv["Name"] . " - " . $count . "/5 matches</a>";
                        $tmpTDstr =  $tmpTDstr . "&nbsp;&nbsp;&nbsp;<a href='addMatchingGaylord_mrg.php?companyID=" .  isset($b2bid) . "&inventoryID=" . $inv["I"] . "'>";

                        //if ( ((int) (6371 * $distC * .621371192) > 301) && (int) (6371 * $distC * .621371192) < 500)

                        //echo "Add</a>";
                        $tmpTDstr =  $tmpTDstr . "Add</a>";

                        //	echo "</font></strong>";
                        $tmpTDstr =  $tmpTDstr . "</font></strong>";

                        $mileage = (int) (6371 * $distC * .621371192);

                        $MGArray[] = array('arrorder' => $mileage, 'arrdet' => $tmpTDstr);
                    }
                } //if count > 4
            } //inv

        } //gaylord

        // Added by Mooneem Jul-13-12 to Bring the green thread at top	
        // Sort the Array based on Mileage	
        $MGArraysort = array();

        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['arrorder'];
        }

        array_multisort($MGArraysort, SORT_NUMERIC, $MGArray);

        foreach ($MGArray as $MGArraytmp2) {
            ?>

    <tr align="center">
        <td colspan="2">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">

                <?php
                        echo $MGArraytmp2['arrdet'];

                        ?>
            </font>
        </td>
    </tr>

    <?php

        }
        echo "</table>";

        // Added by Mooneem Jul-13-12 to Bring the green thread at top	- End here  
        $box1 = array();
        $box1 = array();
        $box1 = array();
        $box1 = array();


        $box1[0] = $row["box1LI"];
        $box1[1] = $row["box1LN"];
        $box1[2] = $row["box1LD"];
        $box1[3] = $row["box1WI"];
        $box1[4] = $row["box1WN"];
        $box1[5] = $row["box1WD"];
        $box1[6] = $row["box1DI"];
        $box1[7] = $row["box1DN"];
        $box1[8] = $row["box1DD"];
        $box1[9] = $row["box1newUsed"];
        $box1[10] = $row["box1printing"];
        $box1[11] = $row["box1Labels"];
        $box1[12] = $row["box1Quantity"];

        $box2[0] = $row["box2LI"];
        $box2[1] = $row["box2LN"];
        $box2[2] = $row["box2LD"];
        $box2[3] = $row["box2WI"];
        $box2[4] = $row["box2WN"];
        $box2[5] = $row["box2WD"];
        $box2[6] = $row["box2DI"];
        $box2[7] = $row["box2DN"];
        $box2[8] = $row["box2DD"];
        $box2[9] = $row["box2newUsed"];
        $box2[10] = $row["box2printing"];
        $box2[11] = $row["box2Labels"];
        $box2[12] = $row["box2Quantity"];

        $box3[0] = $row["box3LI"];
        $box3[1] = $row["box3LN"];
        $box3[2] = $row["box3LD"];
        $box3[3] = $row["box3WI"];
        $box3[4] = $row["box3WN"];
        $box3[5] = $row["box3WD"];
        $box3[6] = $row["box3DI"];
        $box3[7] = $row["box3DN"];
        $box3[8] = $row["box3DD"];
        $box3[9] = $row["box3newUsed"];
        $box3[10] = $row["box3printing"];
        $box3[11] = $row["box3Labels"];
        $box3[12] = $row["box3Quantity"];

        $box4[0] = $row["box4LI"];
        $box4[1] = $row["box4LN"];
        $box4[2] = $row["box4LD"];
        $box4[3] = $row["box4WI"];
        $box4[4] = $row["box4WN"];
        $box4[5] = $row["box4WD"];
        $box4[6] = $row["box4DI"];
        $box4[7] = $row["box4DN"];
        $box4[8] = $row["box4DD"];
        $box4[9] = $row["box4newUsed"];
        $box4[10] = $row["box4printing"];
        $box4[11] = $row["box4Labels"];
        $box4[12] = $row["box4Quantity"];

        //SKIPPING BOX REQUESTED SECTION
        // 	if Request.QueryString("edit") <> "3" then
        //		'in tableFunctions.asp. This is the normal view of the box request info.
        //		viewBoxRequest ($box1, $box2, $box3, $box4, $row["q1"], $row["q2"], $row["q3"], $row["q4"], $row["q5"], $row["q6"], $row["q7"], $row["q8"], $row["q9"], $row["q10"], $row["notes"], $b2bid);
        //	else
        //		'in tableFunctions.asp. This is the editable view. Results go to editTables.asp
        //		viewBoxRequestEdit box1, box2, box3, box4, objRS("q1"), objRS("q2"), objRS("q3"), objRS("q4"), objRS("q5"), objRS("q6"), objRS("q7"), objRS("q8"), objRS("q9"), objRS("q10"), objRS("notes"), Request.QueryString("ID")
        //	end if

        $q = "Select * From boxesrequested Where companyID = '" . isset($b2bid) . "'";
        db_b2b();
        $X = db_query($q);
        while ($fetchboxes = array_shift($X)) {

            if (function_exists('newViewBoxRequest')) {

                newViewBoxRequest(isset($b2bid));
            }

            echo "<br>";
        }

        $q = "Select * From boxesForPickup Where companyID = '" . isset($b2bid) . "'";
        db_b2b();
        $X = db_query($q);
        while ($fetchboxes = array_shift($X)) {

            if (function_exists('viewRescue')) {

                viewRescue(isset($b2bid));
            }

            echo "<br>";
        }



        if (($row["haveNeed"] == "Need Boxes") || ($row["haveNeed"] == "Looking / Have Boxes")) {
        ?>
    <br>
    <a href="selectinventoryitem_mrg.php?loopID=<?php echo $_GET["id"] ?>&companyID=<?php echo isset($b2bid); ?>">Add
        Inventory
        Item</a>
    <br>
    <a href="additemsubmit_mrg.php?loopID=<?php echo $_GET["id"] ?>&companyID=<?php echo isset($b2bid); ?>">Add Freight
        and
        Non-Inventory
        Item</a>
    <br>
    <script language="JavaScript">
    function validate() {
        var obj = document.frm1;
        if (obj.quoteType.value == "") {
            alert("Please select the quote type.");
            obj.quoteType.focus();
            return false;
        }
        if (obj.poNumber.value == "") {
            alert("Please enter the po number.");
            obj.poNumber.focus();
            return false;
        }
        if (obj.rep.value == "") {
            alert("Please select a Representative.");
            obj.rep.focus();
            return false;
        }
        if (obj.TBD.checked == false) {
            if (obj.ship_date.value == "") {
                alert("Please enter the ship date.");
                obj.ship_date.focus();
                return false;
            }
        }
    }
    </script>
    <form method=POST action="createQuoteSubmitLoops_mrg.php" onsubmit="javascript:return validate()" name="frm1">




        <input type=hidden name="companyID" value="<?php echo isset($b2bid) ?>">

        <br>
        <table border="0" cellspacing="1" cellpadding="1" width="650">
            <tr>
                <td colspan="11" bgcolor="#C0CDDA" align="center" width="646">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">QUOTING - BOXES & PRODUCTS
                    </font>
                </td>
            </tr>
            <tr>
                <td align="center" width="30">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">#</font>
                </td>
                <td align="center" width="46">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Edit</font>
                </td>
                <td align="center" width="46">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Dup</font>
                </td>
                <td align="center" width="6">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">X</font>
                </td>
                <td align="center" width="365">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Description</font>
                </td>
                <td align="center" width="57">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">FOB</font>
                </td>
                <td align="center" width="57">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Sale Price</font>
                </td>
                <td align="center" width="35">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Cost</font>
                </td>
                <td align="center" width="42">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Quantity</font>
                </td>
                <td align="center" width="53">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Profit</font>
                </td>
                <td align="center" width="53">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Sort Order</font>
                </td>

            </tr>

            <?php

                    $box = 0;
                    $boxSql = "Select *, lengthInch as A from boxes Where companyID = " . isset($b2bid) . " ORDER BY LengthInch ASC";

                    db_b2b();
                    $dt_view_res2 = db_query($boxSql);
                    while ($objBox = array_shift($dt_view_res2)) {

                    ?>
            <tr bgcolor="#E4E4E4">
                <td width="30">
                    <input type="checkbox" name="selectItem[]" value="<?php echo $objBox["ID"] ?>">
                </td>

                <?php
                            if ($objBox["inventoryID"] > -1) {
                            ?>
                <td width="46">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a
                            href="viewItem_mrg.php?companyID=<?php echo isset($b2bid) ?>&itemID=<?php echo $objBox["ID"] ?>&loopID=<?php echo $_GET["warehouse_id"] ?>">
                            <?php if ($objBox["item"] == "") {
                                                echo "Edit";
                                            } ?>
                            <?php echo $objBox["item"] ?>
                        </a></font>
                </td>
                <td width="5%">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <a
                            href="duplicatebox_mrg.php?bid=<?php echo $objBox["ID"] ?>&id=<?php echo isset($b2bid) ?>">Dup</a>
                    </font>
                </td>
                <td align="right">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <a href="deletebox_mrg.php?bid=<?php echo $objBox["ID"] ?>&id=<?php echo isset($b2bid) ?>">X</a>
                    </font>
                </td>
                <td align="left" width="365">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php

                                        if ($objBox["item"] == "Boxes") {

                                            if ($objBox["lengthNumerator"] != 0) {
                                                echo $objBox["lengthInch"] . " " . $objBox["lengthNumerator"] . "/" . $objBox["lengthDenominator"] . " x ";
                                            } else {
                                                echo $objBox["lengthInch"] . " x ";
                                            }
                                            if ($objBox["widthNumerator"] != 0) {
                                                echo $objBox["widthInch"] . " " . $objBox["widthNumerator"] . "/" . $objBox["widthDenominator"] . " x ";
                                            } else {
                                                echo $objBox["widthInch"] . " x ";
                                            }
                                            if ($objBox["depthNumerator"] != 0) {
                                                echo $objBox["depthInch"] . " " . $objBox["depthNumerator"] . "/" . $objBox["depthDenominator"] . " ";
                                            } else {
                                                echo $objBox["depthInch"] . " ";
                                            }

                                            echo $objBox["newUsed"] . " ";
                                        }

                                        echo $objBox["description"];

                                        if ($objBox["inventoryID"] > -1) { //if the box is connected to a box in Loops
                                            $lq = "SELECT loops_id AS LID from inventory WHERE ID = " . $objBox["inventoryID"];
                                            db_b2b();
                                            $l_res = db_query($lq);
                                            $lbox_id = isset($lrow["LID"]); //this is the id of the box in Loops
                                            //echo $objBox["inventoryID"] . $lbox_id;
                                            $lbq = "SELECT * from loop_boxes WHERE b2b_id = " . $objBox["inventoryID"];
                                            db();
                                            $lb_res = db_query($lbq);
                                            $lbrow = array_shift($lb_res);

                                            if ($lbrow["flyer"] != "") {
                                                echo " <a href='boxpics/" . $lbrow["flyer"] . "' target='_blank'>flyer</a> ";
                                            }
                                        }

                                        ?>

                    </font>
                </td>
                <td align="right" width="57">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        $<?php echo number_format($objBox["ulinePrice"], 2) ?></font>
                </td>
                <td align="right" width="57">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        $<?php echo number_format($objBox["salePrice"], 2) ?></font>
                </td>
                <td align="right" width="35">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        $<?php echo number_format($objBox["cost"], 2) ?></font>
                </td>
                <td align="right" width="42">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $objBox["quantity"] ?>
                    </font>
                </td>
                <td align="right" width="53">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo number_format(($objBox["salePrice"] - $objBox["cost"]) * $objBox["quantity"] - $objBox["shipfinal"], 2) ?>
                    </font>
                </td>
                <td align="right" width="57">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <select size="1" name="sort_order_<?php echo $objBox["ID"] ?>">
                            <?php $i = 0;
                                            while ($i < 100) { ?>
                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                            <?php $i += 1;
                                            } ?>
                        </select>
                </td>
            </tr>



            <?php
                            } else {


                                $boxSql = "Select * from inventory Where ID = " . $objBox["InventoryID"];
                                db_b2b();
                                $dt_view_res3 = db_query($boxSql);
                                $objInv = array_shift($dt_view_res3);

                    ?>
            <td width="46">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $objInv["item"] ?></font>
            </td>
            <td align="left" width="365">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php

                                if ($objInv["lengthFraction"] != "") {
                                    echo $objInv["lengthInch"] . " " . $objInv["lengthFraction"] . " x ";
                                } else {
                                    echo $objInv["lengthInch"] . " x ";
                                }
                                if ($objInv["widthFraction"] != "") {
                                    echo $objInv["widthInch"] . " " . $objInv["widthFraction"] . " x ";
                                } else {
                                    echo $objInv["widthInch"] . " x ";
                                }
                                if ($objInv["depthFraction"] != "") {
                                    echo $objInv["depthInch"] . " " . $objInv["depthFraction"] . " ";
                                } else {
                                    echo $objInv["depthInch"] . " ";
                                }

                                echo "[" . number_format($objInv["cubicFeet"], 2) . " cu ft) ";

                                if ($objInv["newUsed"] != "") {
                                    echo $objInv["newUsed"] . " ";
                                }
                                if ($objInv["printing"] != "") {
                                    echo $objInv["printing"] . " ";
                                }
                                if ($objInv["labels"] != "" and $objInv["labels"] != "No Labels") {
                                    echo $objInv["labels"] . " ";
                                }
                                if ($objInv["writing"] != "" and $objInv["writing"] != "None") {
                                    echo $objInv["writing"] . " ";
                                }
                                if ($objInv["burst"] != "") {
                                    echo $objInv["burst"] . " ";
                                }
                                echo $objInv["description"];




                                ?>

                </font>
            </td>
            <td align="right" width="8%">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo number_format($objBox["salePrice"], 2) ?></font>
            </td>
            <td align="right" width="8%">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo number_format(floatval($objInv["costDollar"] . $objInv["costCents"]), 2) ?>
                </font>
            </td>
            <?php if ($objBox["quantity"] > $objInv["quantity"]) { ?>
            <td align="right" width="8%">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#FF0000"><?php echo $objBox["quantity"] ?>
                </font>
            </td>
            <?php } else { ?>
            <td align="right" width="8%">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $objBox["quantity"] ?>
                </font>
            </td>
            <?php } ?>
            <td align="right" width="8%">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <?php echo number_format(($objBox["salePrice"] - ($objInv["costDollar"] . $objInv["costCents"])) * $objBox["quantity"], 2) ?>
                </font>
            </td>
            <td align="right" width="57">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                    <select size="1" name="sort_order_<?php echo $objBox["ID"] ?>">
                        <?php $i = 0;
                                    while ($i < 100) { ?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php $i += 1;
                                    } ?>
                    </select>
            </td>
            </tr>
            <?php
                            }
                            $box = $box + 1;
                        }
                ?>
            <input type=hidden name="records" value="<?php echo $box ?>">
        </table>

        <table width="650" border="0" cellspacing="1" cellpadding="1">
            <tr align="center">
                <td colspan="2" bgcolor="#C0CDDA" width="539" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1">QUOTING - SHIPPING INFORMATION</font>
                </td>
            </tr>
            <tr bgcolor="#E4E4E4">
                <td width="109" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1">
                        Quote Type</font>
                </td>
                <td align="left" width="427">
                    <select size="1" name="quoteType">
                        <option value="">Please Select</option>
                        <option value="Quote Select">Quote Select</option>
                        <option value="Quote">Quote</option>
                    </select>
                </td>
            </tr>
            <tr bgcolor="#E4E4E4">
                <td width="109" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Sell to Address:</font>
                </td>
                <td align="left" width="427"><select id="quote_billto_add" name="quote_billto_add">
                        <?php
                                $sellto_str = $row["contact"] . " " . $row["address"] . ", " . $row["address2"] . ", " . $row["city"] . ", " . $row["state"] . ", " . $row["zip"];
                                ?>
                        <option value="sellto1">
                            <?php echo $sellto_str; ?>
                        </option>
                        <?php

                                db_b2b();
                                $dt_sellto = db_query("Select * from b2bsellto where companyid = " . $row["ID"] . " order by selltoid");
                                while ($row_sellto = array_shift($dt_sellto)) {

                                    $sellto_str = $row_sellto["name"] . " " . $row_sellto["address"] . ", " . $row_sellto["address2"] . ", " . $row_sellto["city"] . ", " . $row_sellto["state"] . ", " . $row_sellto["zipcode"];
                                ?>
                        <option value="sellto_<?php echo $row_sellto[" selltoid"]; ?>">
                            <?php echo $sellto_str; ?>
                        </option>
                        <?php }
                                ?>
                    </select>
                </td>
            </tr>
            <tr bgcolor="#E4E4E4">
                <td width="109" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1">PO Number</font>
                </td>
                <td align="left" width="427">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <input size="20" name="poNumber" value="NA">
                    </font>
                </td>
            </tr>
            <tr bgcolor="#E4E4E4">
                <td width="109" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1">
                        Terms</font>
                </td>
                <td align="left" width="427">
                    <select size="1" name="terms">
                        <option></option>
                        <?php if (isset($objRS["terms"]) == "Prepaid") { ?>
                        <option value="Prepaid" selected>Prepaid</option>
                        <?php } else { ?>
                        <option value="Prepaid">Prepaid</option>
                        <?php } ?>
                        <?php if (isset($objRS["terms"]) == "Due on Receipt") { ?>
                        <option value="Due on Receipt" selected>Due on Receipt</option>
                        <?php } else { ?>
                        <option value="Due on Receipt">Due on Receipt</option>
                        <?php } ?>
                        <?php if (isset($objRS["terms"]) == "Net 10") { ?>
                        <option value="Net 10" selected>Net 10</option>
                        <?php } else { ?>
                        <option value="Net 10">Net 10</option>
                        <?php } ?>
                        <?php if (isset($objRS["terms"]) == "Net 15") { ?>
                        <option value="Net 15" selected>Net 15</option>
                        <?php } else { ?>
                        <option value="Net 15">Net 15</option>
                        <?php } ?>
                        <?php if (isset($objRS["terms"]) == "Net 20") { ?>
                        <option value="Net 20" selected>Net 20</option>
                        <?php } else { ?>
                        <option value="Net 20">Net 20</option>
                        <?php } ?>
                        <?php if (isset($objRS["terms"]) == "Net 25") { ?>
                        <option value="Net 25" selected>Net 25</option>
                        <?php } else { ?>
                        <option value="Net 25">Net 25</option>
                        <?php } ?>
                        <?php if (isset($objRS["terms"]) == "Net 30") { ?>
                        <option value="Net 30" selected>Net 30</option>
                        <?php } else { ?>
                        <option value="Net 30">Net 30</option>
                        <?php } ?>
                        <?php if (isset($objRS["terms"]) == "Net 45") { ?>
                        <option value="Net 45" selected>Net 45</option>
                        <?php } else { ?>
                        <option value="Net 45">Net 45</option>
                        <?php } ?>

                        <?php if (isset($objRS['terms']) == "Net 30 EOM +1") { ?>
                        <option value="Net 30 EOM +1" selected>Net 30 EOM +1</option>
                        <?php    } else {     ?>
                        <option value="Net 30 EOM +1">Net 30 EOM +1</option>
                        <?php } ?>

                        <?php if (isset($objRS['terms']) == "Net 45 EOM +1") {  ?>
                        <option value="Net 45 EOM +1" selected>Net 45 EOM +1</option>
                        <?php  } else {     ?>
                        <option value="Net 45 EOM +1">Net 45 EOM +1</option>
                        <?php  }  ?>

                    </select>
                </td>
            </tr>
            <tr bgcolor="#E4E4E4">
                <td width="109" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Representative</font>
                </td>
                <td align="left" width="427" height="13">
                    <?php if (function_exists('putEmployee')) { ?>
                    <?php echo putEmployee("rep"); ?>
                    <?php } ?>
                </td>
            </tr>
            <tr bgcolor="#E4E4E4">
                <td width="109" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1">
                        Ship Date</font>
                </td>
                <td align="left" width="427">
                    <input onclick="javascript:document.frm1.ship_date.value = '';" type=checkbox name=TBD
                        <?php if ($objRS["TBD"] = 1) {
                                                                                                                        echo " checked ";
                                                                                                                    } ?>value=1 value="ON">TBD
                    <br>
                    <input id="date_of_activity" name="ship_date" type="text" value="" readonly>&nbsp;<a
                        href="Javascript:void(0);"><img src="images/calendar.jpg" border="0" align="absmiddle"
                            alt="Calendar" id="date_of_activity_calendar" style="cursor: hand;"></a>
                    <script type="text/javascript">
                    Calendar.setup({
                        inputField: "date_of_activity", // id of the input field
                        ifFormat: "%m-%d-%Y", // format of the input field
                        button: "date_of_activity_calendar", // trigger for the calendar (button ID)
                        align: "Br", // alignment (defaults to "Bl")
                        singleClick: true
                    });
                    </script>
                </td>
            </tr>
            <tr bgcolor="#E4E4E4">
                <td width="109" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1">
                        Via</font>
                </td>
                <td align="left" width="427" height="13">
                    <select size="1" name="via">
                        <option></option>
                        <?php if ($objRS["via"] == "Pickup") { ?>
                        <option selected>Pickup</option>
                        <?php } else { ?>
                        <option>Pickup</option>
                        <?php } ?>
                        <?php if ($objRS["via"] == "Third Party") { ?>
                        <option selected>Third Party</option>
                        <?php } else { ?>
                        <option>Third Party</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr bgcolor="#E4E4E4">
                <td width="109" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1">Notes</font>
                </td>
                <td align="left" width="427" height="13"><textarea rows="3" name="notes"
                        cols="41"><?php echo $objRS["quoteNote"] ?></textarea></td>
            </tr>
            <tr bgcolor="#E4E4E4">
                <td width="109" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1">Delivery Included</font>
                </td>
                <td align="left" height="13">
                    <font face="Arial, Helvetica, sans-serif" size="1"><input type="checkbox" name="free_shipping"
                            value="1"></font>
                </td>
            </tr>
            <tr align="center" bgcolor="#E4E4E4">
                <td colspan="2" align="center">
                    <font face="Arial, Helvetica, sans-serif" size="1">
                        <input type=submit value="Create Quote">
                    </font>
                </td>
            </tr>
        </table>
        <br>
        <br>
    </form>



    <br>
    <table border="0" width="300" cellspacing="2" cellpadding="1" bgcolor="#FFFFFF">
        <tr>
            <td width="100%" bgcolor="#C0CDDA" align="center">
                <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">QUOTES GENERATED</font>
            </td>
        </tr>

        <?php

                $quotes_archive_date = "";
                $query = "SELECT variablevalue FROM tblvariable where variablename = 'quotes_archive_date'";
                db();
                $dt_view_res3 = db_query($query);
                while ($objQuote = array_shift($dt_view_res3)) {
                    $quotes_archive_date = $objQuote["variablevalue"];
                }

                $query = "SELECT * FROM quote WHERE companyID=" . isset($b2bid) . " ORDER BY ID DESC";
                db_b2b();
                $dt_view_res3 = db_query($query);
                while ($objQuote = array_shift($dt_view_res3)) {

                    if (is_null($objQuote["quote_total"]))
                        $qtotalamt = 0;
                    else
                        $qtotalamt = $objQuote["quote_total"];

                ?>
        <form name="frmQuote_<?php echo $objQuote["ID"] ?>" method="post" action="updateQuoteStatus_mrg.php">
            <input type="hidden" name="quote_id" value="<?php echo $objQuote["ID"] ?>">
            <input type="hidden" name="companyID" value="<?php echo isset($b2bid) ?>">
            <tr>
                <td width="100%" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        QUOTE ID: <?php echo ($objQuote["ID"] + 3770); ?><br>
                        PO No: <?php echo $objQuote["poNumber"] ?><br>
                        Quote Date: <?php echo timestamp_to_date($objQuote["quoteDate"]); ?><br>

                        <?php
                                    if ($objQuote["filename"] != "") {

                                        echo $objQuote["quoteType"] ?>
                        <br>
                        <?php
                                        $archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
                                        $quote_date = new DateTime(date("Y-m-d", strtotime($objQuote["quoteDate"])));

                                        if ($quote_date < $archeive_date) {
                                        ?>
                        <a
                            href="https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/<?php echo $objQuote["filename"] ?>">View
                            PDF</a>
                        <?php } else { ?>
                        <a href="quotes/<?php echo $objQuote["filename"] ?>">View PDF</a><br>
                        <?php }
                                    } else {
                                        if ($objQuote["quoteType"] == "Quote") {
                                        ?>
                        Quote Type:
                        <a
                            href="fullquote_mrg.php?ID=<?php echo $objQuote["ID"] ?>"><?php echo $objQuote["quoteType"] ?></a><br>
                        <?php
                                        } elseif ($objQuote["quoteType"] == "Quote Select") {
                                        ?>
                        Quote Type:
                        <a href="http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=<?php echo $objQuote["ID"] ?>"
                            target="_blank"><?php echo $objQuote["quoteType"] ?></a><br>
                        <?php
                                        } else {
                                        ?>
                        Quote Type: <?php echo $objQuote["quoteType"] ?><br>
                        <?php }
                                    } ?>
                        Quote Amount: <?php echo number_format($qtotalamt, 2) ?><br>
                        Status:<select size="1" name="quote_status">
                            <?php
                                        $box = 0;

                                        $boxSql = "Select * from quote_status Where status=1";
                                        db_b2b();
                                        $dt_view_res4 = db_query($boxSql);
                                        while ($objQStatus = array_shift($dt_view_res4)) {

                                            if ($objQStatus["qid"] == $objQuote["qstatus"])
                                                $strSelected = " selected";
                                            else
                                                $strSelected = "";

                                        ?>

                            <option value="<?php echo $objQStatus["qid"] ?>" <?php echo $strSelected ?>>
                                <?php echo $objQStatus["status_name"] ?></option>
                            <?php
                                        }
                                        ?>
                        </select> <input type="submit" value="Update" name="B1">
                    </font>
                </td>
            </tr>
        </form>
        <?php } ?>
    </table>
    <?php }
    } // view quote end here
    ?>