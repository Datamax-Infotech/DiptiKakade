<br /><br />

<?php

$start_date = "";
$x = "Select * from companyInfo Where ID = " . isset($b2bid);
db_b2b();
$dt_view_res = db_query($x);
$row = array_shift($dt_view_res);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E4E4E4">
    <tr align="center">
        <td colspan="2" bgcolor="#C0CDDA">
            <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Assignments, Status, Next Steps and
                Notifications</font>
        </td>
    </tr>
    <tr>
        <td colspan="2" valign="middle" align="center">
            <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><br>
            </font>
        </td>
    <tr>
        <td>
            <form method="GET" ENCTYPE="multipart/form-data" name="frmm" action="assignto_mrg.php">
                <input type=hidden name="companyID" value="<?php echo isset($b2bid) ?>">
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Account Owner</font><br />
                <select size="1" name="assignid1">
                    <option value="0">Assign To</option>
                    <?php

                    $arr = explode(",", $row["assignedto"]);
                    $qassign = "SELECT * FROM employees WHERE status='Active'";
                    db_b2b();
                    $dt_view_res_assign = db_query($qassign);
                    while ($res_assign = array_shift($dt_view_res_assign)) {
                        if ($row["assignedto"] != $res_assign["employeeID"]) {
                    ?>
                    <option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
                    <?php
                        } else { ?>
                    <option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?>
                    </option>
                    <?php
                        }
                    }
                    ?>
                    <option <?php if ($row["assignedto"] == -1) echo " selected "; ?> value="-1">Jesus</option>
                </select>

                <br><br>
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Also Viewable By</font><br />
                <select size="1" name="assignid2">
                    <option value="0">Assign To</option>
                    <?php

                    $qassign = "SELECT * FROM employees WHERE status='Active'";
                    db_b2b();
                    $dt_view_res_assign = db_query($qassign);
                    while ($res_assign = array_shift($dt_view_res_assign)) {
                        if ($row["viewable1"] != $res_assign["employeeID"]) {
                    ?>
                    <option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
                    <?php
                        } else { ?>
                    <option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?>
                    </option>
                    <?php
                        }
                    }
                    ?>
                    <option <?php if ($row["viewable1"] == -1) echo " selected "; ?> value="-1">Jesus</option>
                </select>
                <br />
                <select size="1" name="assignid3">
                    <option value="0">Assign To</option>
                    <?php

                    $qassign = "SELECT * FROM employees WHERE status='Active'";
                    db_b2b();
                    $dt_view_res_assign = db_query($qassign);
                    while ($res_assign = array_shift($dt_view_res_assign)) {
                        if ($row["viewable2"] != $res_assign["employeeID"]) {
                    ?>
                    <option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
                    <?php
                        } else { ?>
                    <option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?>
                    </option>
                    <?php
                        }
                    }
                    ?>
                    <option <?php if ($row["viewable2"] == -1) echo " selected "; ?> value="-1">Jesus</option>
                </select>

                <br />
                <select size="1" name="assignid4">
                    <option value="0">Assign To</option>
                    <?php

                    $qassign = "SELECT * FROM employees WHERE status='Active'";
                    db_b2b();
                    $dt_view_res_assign = db_query($qassign);
                    while ($res_assign = array_shift($dt_view_res_assign)) {
                        if ($row["viewable3"] != $res_assign["employeeID"]) {
                    ?>
                    <option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
                    <?php
                        } else { ?>
                    <option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?>
                    </option>
                    <?php
                        }
                    }
                    ?>
                    <option <?php if ($row["viewable3"] == -1) echo " selected "; ?> value="-1">Jesus</option>
                </select>

                <br />
                <select size="1" name="assignid5">
                    <option value="0">Assign To</option>
                    <?php

                    $qassign = "SELECT * FROM employees WHERE status='Active'";
                    db_b2b();
                    $dt_view_res_assign = db_query($qassign);
                    while ($res_assign = array_shift($dt_view_res_assign)) {
                        if ($row["viewable4"] != $res_assign["employeeID"]) {
                    ?>
                    <option value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?></option>
                    <?php
                        } else { ?>
                    <option selected value="<?php echo $res_assign["employeeID"] ?>"><?php echo $res_assign["name"] ?>
                    </option>
                    <?php
                        }
                    }
                    ?>
                    <option <?php if ($row["viewable4"] == -1) echo " selected "; ?> value="-1">Jesus</option>
                </select>

                <input type="submit" value="Update" name="B1">&nbsp;
            </form>
        </td>
    </tr>


    </tr>
    <?php if (($row["haveNeed"] == "Need Boxes") || ($row["haveNeed"] == "Looking / Have Boxes") || ($row["haveNeed"] == "Have Boxes")) { ?>
    <tr>
        <td width="12%" colspan="2">
            <hr />
        </td>
    </tr>
    <tr>
        <td width="12%" valign="middle" colspan="2" align="left">
            <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
                <form method="POST" action="updatestatus_mrg.php">
                    <input type=hidden name="companyID" value="<?php echo isset($b2bid) ?>">
                    <input type=hidden name="loopID" value="<?php echo $_GET["id"] ?>">

                    Account Status<br />
                    <select size="1" name="status">
                        <?php

                            $status = "Select * from status order by sort_order";
                            db_b2b();
                            $dt_view_res4 = db_query($status);
                            while ($objStatus = array_shift($dt_view_res4)) {

                            ?>
                        <option value="<?php echo $objStatus["id"] ?>" <?php if ($objStatus["id"] == $row["status"])
                                                                                    echo " selected "; ?>>
                            <?php echo $objStatus["name"] ?> </option>
                        <?php
                            }
                            ?>

                    </select><input type="submit" value="Update" name="B1">&nbsp;
                </form>


                <form method="POST" action="updateNextStep_mrg.php" name="nextstep" id="nextstep">
                    Next Step <br />If I have not heard back by then, I will do THIS:<br>
                    <input type=hidden name="companyID" value="<?php echo isset($b2bid) ?>">
                    <input type=hidden name="loopID" value="<?php echo $_GET["id"] ?>">
                    <input type="text" name="next_step" size="80" value="<?php echo $row["next_step"] ?>"><br />on THIS
                    date:<br>
                    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
                    <script LANGUAGE="JavaScript">
                    document.write(getCalendarStyles());
                    </script>
                    <script LANGUAGE="JavaScript">
                    var cal1xx = new CalendarPopup("listdiv");
                    cal1xx.showNavigationDropdowns();
                    </script>
                    <?php

                        $start_date = isset($row["next_date"]) ? strtotime($row["next_date"]) : "";

                        ?>

                    <input type="text" name="start_date" size="11"
                        value="<?php echo (isset($row["next_date"]) && $row["next_date"] != "") ? date('m/d/Y', $start_date) : "" ?>">
                    <a href="#"
                        onclick="cal1xx.select(document.nextstep.start_date,'anchor1xx','MM/dd/yyyy'); return false;"
                        name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>


                    <input type="submit" value="Update" name="B1">&nbsp;
                    <div ID="listdiv"
                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
                </form>
        </td>
    </tr>
    <?php } ?>



    <tr>
        <td width="12%" valign="middle" colspan="2" align="left">
            <script language="javascript">
            function validate() {
                if (document.frmm.employeed.value == "") {
                    alert("Please Select an Employee.");
                    document.frmm.employeed.focus();
                    return false;
                }
            }
            </script>
            <form method="POST" ENCTYPE="multipart/form-data" name="frmm" action="emailuser_mrg.php"
                onsubmit="javascript:return validate()">
                <input type=hidden name="companyID" value="<?php echo isset($b2bid) ?>">
                <input type=hidden name="loopID" value="<?php echo $_GET["id"] ?>">
                <hr />
                <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Send Record's Link to Another User
                </font><br />
                <select size="1" name="employeed">
                    <option value="">Choose Recipient</option>
                    <?php

                    $qr2 = "SELECT * FROM employees WHERE status='Active'";
                    db_b2b();
                    $dt_view_res6 = db_query($qr2);
                    while ($remp2 = array_shift($dt_view_res6)) {
                    ?>
                    <option value="<?php echo $remp2["employeeID"] ?>"><?php echo $remp2["name"] ?></option>
                    <?php
                    }
                    ?>
                </select>
                <input type="submit" value="Send Link" name="B1">&nbsp;
            </form>
        </td>
    </tr>

</table>