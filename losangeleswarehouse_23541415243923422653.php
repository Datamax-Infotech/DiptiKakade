<?php
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
require("inc/functions_mysqli.php");
?>
<?php
$urlRefresh = "losangeleswarehouse_23541415243923422653.php";

header("Refresh: 10; URL=" . $urlRefresh); // redirect in 5 seconds

$this_warehouse_id = 925;

$inbound_warehouses = "907"; // "ID, ID, ID, ID"

$this_employee = "UCB-LA";

$page_name = "UsedCardboardBoxes.com - Los Angeles";

?>
<!DOCTYPE html>
<html>
<title><?php echo $page_name; ?> - Dashboard</title>

<head>
    <style type="text/css">
    span.infotxt:hover {
        text-decoration: none;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt span {
        position: absolute;
        left: -9999px;
        margin: 20px 0 0 0px;
        padding: 3px 3px 3px 3px;
        z-index: 6;
    }

    span.infotxt:hover span {
        left: 25%;
        background: #ffffff;
    }

    span.infotxt span {
        position: absolute;
        left: -9999px;
        margin: 1px 0 0 0px;
        padding: 0px 3px 3px 3px;
        border-style: solid;
        border-color: black;
        border-width: 1px;
    }

    span.infotxt:hover span {
        margin: 1px 0 0 170px;
        background: #ffffff;
        z-index: 6;
    }
    </style>
    <script language="JavaScript">
    function chkssn() {

        if (document.getElementById('ssn_txt').value.trim() == "") {

            alert("Please enter the last four digit of your SSN.");

            return false;

        } else {

            return true;

        }

    }



    function chkssn_logout(id) {

        if (document.getElementById('ssn_txt_logout' + id).value.trim() == "") {

            alert("Please enter the last four digit of your SSN.");

            return false;

        } else {

            return true;

        }

    }



    function confirmationRequest(a, b, c) {

        var answer = confirm("Request Pickup of Trailer #" + a + "?")

        if (answer) {

            window.location = "<?php echo $urlRefresh ?>?action=request&req_id=" + b + "&trailer_no=" + a + "&dock=" +
                c;

        } else {

            alert("Request Cancelled");

        }

    }



    function confirmationDelivery(a, b, c) {

        var answer = confirm("Confirm Delivery of Trailer #" + a + " to UCB warehouse?")

        if (answer) {

            window.location = "<?php echo $urlRefresh ?>?action=confirm&conf_id=" + b + "&trailer_no=" + a + "&dock=" +
                c;

        } else {

            alert("Request Cancelled");

        }

    }



    function timedRefresh(timeoutPeriod) {



        setTimeout("location.reload(true);", timeoutPeriod);

    }
    </script>
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
function FormCheck()

{

    if (document.BOLForm.trailer_no.value == "" |

        document.BOLForm.dock.value == "" |

        document.BOLForm.fullname.value == "")

    {

        alert("Please Complete All Field.\n Need help? Call 1-888-BOXES-88");

        return false;

    }

}
</SCRIPT>
<script type="text/javascript">
function update_cart()

{

    var x

    var total = 0

    var order_total

    for (x = 1; x <= 10; x++)

    {

        item_total = document.getElementById("weight_" + x)

        total = total + item_total.value * 1

    }

    order_total = document.getElementById("order_total")

    document.getElementById("order_total").value = total.toFixed(0)

}
</script>

<body onload="JavaScript:timedRefresh(300000);">
    <?php
   if ($_REQUEST["action"] == "confirm") {

      $str_email = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td><p align=\"center\"><a href=\"http://www.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";

      $str_email .= "Dear UsedCardboardBoxes.com,<br><br>This email is to confirm delivery of Trailer # " . $_REQUEST["trailer_no"] . ". Details below:<br><br>";

      $str_email .= "Dock #:  <b>" . $_REQUEST["dock"] . "</b> <br>";

      $str_email .= "Trailer #:  <b>" . $_REQUEST["trailer_no"] . "</b> <br><br>";

      $str_email .= "Delivered to:<br><b>Used Cardboard Boxes<br>2011 S. Highway 61<br>Hannibal, MO 63401</b><br>";

      $str_email .= "Best Regards<br>";

      $str_email .= "UsedCardboardBoxes.com<br>";

      $str_email .= "</font></td></tr><tr><td><p align=\"center\"><img width=\"650\" height=\"87\" src=\"https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr></table></body></html>";



      $recipient = "davidkrasnow@usedcardboardboxes.com, martymetro@usedcardboardboxes.com";

      $subject = "Notification: Trailer Delivered to UCB - Clubhouse Rd";

      $mailheadersadmin = "From: UsedCardboardBoxes.com <operations@UsedCardboardBoxes.com>\n";

      $mailheadersadmin .= "MIME-Version: 1.0\r\n";

      $mailheadersadmin .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

      $resp = sendemail_php_function(null, '', $recipient, "", "", "ucbemail@usedcardboardboxes.com", "Operations Usedcardboardboxes", "operations@UsedCardboardBoxes.com", $subject, $str_email);

      $sql3ud = "UPDATE loop_transaction SET pa_warehouse = '" . $this_warehouse_id . "', bol_file = 'No BOL', bol_employee = '" . $this_employee . "', bol_date = '" . date("m/d/Y") . "' WHERE id = " . $_REQUEST["conf_id"];

      db();
      $result3ud = db_query($sql3ud);

      $sql3ud = "UPDATE loop_transaction SET cp_notes = 'Delivery Confirmed via Warehouse Dashboard', cp_employee = '" . $this_employee . "', cp_date = '" . date("m/d/Y") . "' WHERE id = " . $_REQUEST["conf_id"];

      db();
      $result3ud = db_query($sql3ud);

      redirect($urlRefresh);;
   }

   $fraud_found = 0;

   $emp_login_msg = "";

   if ($_REQUEST["action"] == "clockin") {

      if ($_REQUEST["worker"] > 0) {

         $rec_bypass = "no";

         $sql_chk = "select user_pwd, user_masterpin from loop_workers where id = " . $_REQUEST["worker"];

         db();
         $result_chk = db_query($sql_chk);

         $rec_found = "notfound";

         $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";

         while ($row_chk = array_shift($result_chk)) {

            $rec_found = "found";

            if ($row_chk["user_pwd"] != "0") {

               if ($row_chk["user_pwd"] != $_REQUEST["ssn_txt"]) {

                  $rec_bypass = "err";

                  $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
               } else {

                  $emp_login_msg = "";
               }
            }
         }



         if ($rec_bypass == "err") {

            $sql_chk = "select variablevalue from tblvariable where variablename = 'master_pin'";

            db();
            $result_chk = db_query($sql_chk);

            while ($row_chk = array_shift($result_chk)) {

               if ($row_chk["variablevalue"] != $_REQUEST["ssn_txt"]) {

                  $rec_bypass = "err";

                  $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
               } else {

                  $emp_login_msg = "";

                  $rec_bypass = "no";
               }
            }
         }



         if ($rec_bypass == "no") {

            $sql3ud = "INSERT INTO loop_timeclock (`worker_id` ,`warehouse_id` ,`time_in`, `type`, `ipaddress`) VALUES ('" . $_REQUEST["worker"] . "', '$this_warehouse_id', NOW() - INTERVAL 2 HOUR, '" . $_REQUEST["type"] . "', '" . $_SERVER["REMOTE_ADDR"] . "')";

            db();
            $result3ud = db_query($sql3ud);

            $ipcheckqery = "select * from timeclock_check_ip where loop_warehouse_id=925 and ipaddress='" . $_SERVER["REMOTE_ADDR"] . "'";

            db();
            $ipcheckqery_r = db_query($ipcheckqery);

            $row_ipcheck = array_shift($ipcheckqery_r);

            if (tep_db_num_rows($ipcheckqery_r) > 0) {

               $fraud_found = 0;

               redirect($urlRefresh);
            } else {

               $worker_selected = $_REQUEST["worker"];

               $fraud_found = 1;
            }
         }
      }
   }

   if ($_REQUEST["action"] == "clockout") {

      $rec_bypass = "no";

      $sql_chk = "select user_pwd, user_masterpin from loop_workers where id = " . $_REQUEST["worker_clockout"];

      db();
      $result_chk = db_query($sql_chk);

      $rec_found = "notfound";

      $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";

      while ($row_chk = array_shift($result_chk)) {

         $rec_found = "found";

         if ($row_chk["user_pwd"] != "0") {

            if ($row_chk["user_pwd"] != $_REQUEST["ssn_txt_logout"]) {

               $rec_bypass = "err";

               $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
            } else {

               $emp_login_msg = "";
            }
         }
      }



      if ($rec_bypass == "err") {

         $sql_chk = "select variablevalue from tblvariable where variablename = 'master_pin'";

         db();
         $result_chk = db_query($sql_chk);

         while ($row_chk = array_shift($result_chk)) {

            if ($row_chk["variablevalue"] != $_REQUEST["ssn_txt_logout"]) {

               $rec_bypass = "err";

               $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
            } else {

               $emp_login_msg = "";

               $rec_bypass = "no";
            }
         }
      }



      if ($rec_bypass == "no") {

         $sql3ud = "UPDATE loop_timeclock SET time_out = NOW() - INTERVAL 2 HOUR, ipaddress_clkout = '" . $_SERVER["REMOTE_ADDR"] . "' WHERE id = " . $_REQUEST["id"];

         db();
         $result3ud = db_query($sql3ud);

         $ipcheckqery = "select * from timeclock_check_ip where loop_warehouse_id=925 and ipaddress='" . $_SERVER["REMOTE_ADDR"] . "'";

         db();
         $ipcheckqery_r = db_query($ipcheckqery);

         $row_ipcheck = array_shift($ipcheckqery_r);

         if (tep_db_num_rows($ipcheckqery_r) > 0) {

            $fraud_found = 0;

            //echo $sql3ud;

            redirect($urlRefresh);
         } else {

            $worker_selected = $_REQUEST["worker_clockout"];

            $fraud_found = 1;
         }
      }
   }

   //Time-clock fraud email send

   //

   if ($fraud_found == 1) {

      $query = "SELECT loop_workers.name, loop_workers.id, loop_warehouse.id AS whid, loop_warehouse.company_name AS wh_name FROM loop_workers INNER JOIN loop_warehouse ON loop_workers.warehouse_id = loop_warehouse.id WHERE loop_workers.id=" . $worker_selected;

      db();
      $res = db_query($query);

      $w_row = array_shift($res);

      $worker_name = $w_row["name"];

      $worker_loc = $w_row["wh_name"];



      //send email

      $fraud_email = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td><p align=\"center\"><a href=\"http://www.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";



      if ($_REQUEST["action"]   == "clockout") {

         $fraud_email .= "Dear David,<br><br>Employee <b>logout</b> from different IP. Below are details,<br><br>";
      } else {

         $fraud_email .= "Dear David,<br><br>Employee <b>login</b> from different IP. Below are details,<br><br>";
      }



      $fraud_email .= "Name: <b>" . $worker_name . "</b><br>";

      $fraud_email .= "IP Address:  <b>" . $_SERVER["REMOTE_ADDR"] . "</b> <br><br>";

      $fraud_email .= "Location:  <b>UCB - Los Angeles</b> <br><br>";



      //$str_email.= "If you have any questions, please contact David Krasnow at 310-402-8059<br><br>";

      $fraud_email .= "Best Regards<br>";

      $fraud_email .= "UsedCardboardBoxes.com<br>";

      $fraud_email .= "</font></td></tr><tr><td><p align=\"center\"><img width=\"650\" height=\"87\" src=\"https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr></table></body></html>";



      $f_recipient = "davidkrasnow@usedcardboardboxes.com";

      //$f_recipient = "prasad.brid@gmail.com";

      $f_subject = "Time-Clock fraud warning";

      $mailheadersadmin1 = "From: UsedCardboardBoxes.com <operations@UsedCardboardBoxes.com>\n";

      $mailheadersadmin1 .= "MIME-Version: 1.0\r\n";

      $mailheadersadmin1 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";



      redirect($urlRefresh);
   }

   //End email time-clock fraud

   ?>
    <!---- TABLE TO FORMAT ----------->
    <table>
        <tr>
            <td>
                <img src="ucb_logo.jpg">
            </td>
            <td align=center colspan="3">
                <font face="Ariel" size="5">
                    <b>UsedCardboardBoxes.com<br></b>
                    Dashboard Report for:<br>
                    <b><i><?php echo $page_name; ?></i></b>
                    </i>
            </td>
            <td colspan="20" align="right">
                <img src="new_interface_help.gif">
            </td>
        </tr>
        <tr>
            <td valign="top">
            </td>
            <td width="100">&nbsp;
            </td>
            <td valign="top">
                <!--------------- EMPLOYEE TABLE ---------------->
                <form method="post" action="<?php echo $urlRefresh ?>" onsubmit="return chkssn();">
                    <input type=hidden name="action" value="clockin">
                    <table cellSpacing="1" cellPadding="1" border="0" width="700px;">
                        <tr align="middle">
                            <td colSpan="5" class="style7">
                                <b>WHO'S WORKING?</b>
                            </td>
                        </tr>
                        <tr align="middle">
                            <td colspan="5">
                                <?php echo $emp_login_msg; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="style17" align="center">
                                <b>NAME</b>
                            </td>
                            <td class="style17" align="center">
                                <b>TIME IN</b>
                            </td>
                            <td class="style17" align="center">
                                <b>TYPE</b>
                            </td>
                            <td class="style17" align="center">
                                <b>IP</b>
                            </td>
                            <td class="style5" align="center">
                                <b>LOGOUT</b>
                            </td>
                        </tr>
                        <tr vAlign="center">
                            <td bgColor="#e4e4e4" class="style3" align="center">
                                <select id="worker" name="worker">
                                    <option value="-1">Select Worker</option>
                                    <?php

                           $query = " SELECT * FROM loop_workers WHERE warehouse_id = " . $this_warehouse_id . " and active = 1 and ";

                           $query .= " id not in (select worker_id from loop_timeclock where warehouse_id = " . $this_warehouse_id . " and time_out = '0000-00-00 00:00:00') ORDER BY name ASC";

                           db();
                           $res = db_query($query);

                           while ($row = array_shift($res)) {



                           ?>
                                    <option value="<?php echo $row["id"] ?>"><?php echo $row["name"] ?></option>
                                    <?php
                           }

                           ?>
                                </select>
                            </td>
                            <td bgColor="#e4e4e4" class="style3" align="center">
                                <select id="type" name="type">
                                    <option value="Office">Office</option>
                                    <option value="Training">Training</option>
                                    <option value="PTO">PTO</option>
                                    <option value="Holiday">Holiday</option>

                                </select>
                            </td>
                            <?php
                     //$date1 = mktime(date("H") + 2, date("i"), date("s"), date("m"), date("d"), date("Y"));
                     $date1 = mktime(
                        (int)date("H") + 2, 
                        (int)date("i"), 
                        (int)date("s"), 
                        (int)date("m"), 
                        (int)date("d"), 
                        (int)date("Y")
                    );
                    
                     ?>
                            <td bgColor="#e4e4e4" class="style3" align="center" colspan="3">
                                SSN (last 4 digit) <input type="password" name="ssn_txt" id="ssn_txt" value=""
                                    size="4" />
                                <input style="cursor:pointer;" type=submit value="CLOCK IN">
                            </td>
                        </tr>
                </form>
                <?php
            $query = "SELECT loop_timeclock.id AS A, loop_workers.name AS B, loop_timeclock.time_in AS C, loop_timeclock.type AS D, loop_timeclock.ipaddress AS IP, loop_timeclock.worker_id FROM loop_timeclock INNER JOIN loop_workers ON loop_timeclock.worker_id = loop_workers.id WHERE loop_timeclock.time_out = '0000-00-00 00:00:00' AND loop_timeclock.warehouse_id = " . $this_warehouse_id . " ORDER BY loop_timeclock.time_in ASC";

            db();
            $res = db_query($query);

            while ($row = array_shift($res)) {

            ?>
                <form method="post" action="<?php echo $urlRefresh ?>"
                    onsubmit="return chkssn_logout(<?php echo $row["A"] ?>);">
                    <input type=hidden name="action" value="clockout">
                    <input type=hidden name="id" value="<?php echo $row["A"] ?>">
                    <input type=hidden name="worker_clockout" value="<?php echo $row["worker_id"] ?>">
        <tr vAlign="center">
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo $row["B"]; ?></td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo date('h:i:s A m/d/Y', strtotime($row["C"])); ?>
            </td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo $row["D"]; ?>
            </td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <a href="http://whatismyipaddress.com/ip/<?php echo $row["IP"]; ?>"
                    target="_blank"><?php echo $row["IP"]; ?>
            </td>
            <td bgColor="#e4e4e4" align=middle class="style3" width="250px;">
                SSN (last 4 digit) <input type="password" name="ssn_txt_logout"
                    id="ssn_txt_logout<?php echo $row["A"] ?>" value="" size="4" />
                <input style="cursor:pointer;" type=submit value="Clock Out">
            </td>
        </tr>
        </form>
        <?php
            }
   ?>
    </table>
    <!--------------- END EMPLOYEE TABLE ---------------->
    <br>
    <!--------------- PRODUCTION TABLE ---------------->
    <table cellSpacing="1" cellPadding="1" border="0">
        <?php
      if (date("d") < 16) {
         $dt = date('Y-m-01');

         $dttitle = date('m/01/Y');
      } else {
         $dt = date('Y-m-16');

         $dttitle = date('m/16/Y');
      }



      $x = 1;

      $query = "SELECT DISTINCT worker_id FROM loop_timeclock WHERE (loop_timeclock.type LIKE '%box_sorting' OR loop_timeclock.type LIKE '%Forklift' OR loop_timeclock.type LIKE '%kits' OR loop_timeclock.type LIKE '%Training') AND loop_timeclock.warehouse_id = $this_warehouse_id ";

      if ($_GET["start_date"] != "qqq") {

         $query .= " AND time_in BETWEEN '" . $dt . "'";
      }

      if ($_GET["end_date"] != "qqqq") {

         $query .= " AND NOW()";
      }

      db();
      $resq = db_query($query);

      while ($rowq = array_shift($resq)) {

         $query = "SELECT name, loop_workers.rate_cost AS RC, SUM(TIME_TO_SEC(TIMEDIFF(time_out,time_in)))/3600 AS DT FROM loop_timeclock LEFT JOIN loop_workers ON loop_timeclock.worker_id = loop_workers.id WHERE (loop_timeclock.type LIKE '%box_sorting' OR loop_timeclock.type LIKE '%Forklift' OR loop_timeclock.type LIKE '%kits' OR loop_timeclock.type LIKE '%Training') AND loop_timeclock.worker_id = " . $rowq["worker_id"];

         if ($_GET["start_date"] != "qqq") {

            $query .= " AND time_in BETWEEN '" . $dt . "'";
         }

         if ($_GET["end_date"] != "qqqq") {

            $query .= " AND NOW()";
         }

         db();
         $res = db_query($query);

         $row = array_shift($res);

         $name = $row["name"];

         $hours = $row["DT"];

         $query = "SELECT SUM( rate * production) AS P FROM loop_timeclock_production WHERE worker_id =  " . $rowq["worker_id"];

         if ($_GET["start_date"] != "q") {

            $query .= " AND date BETWEEN '" . $dt . "'";
         }

         if ($_GET["end_date"] != "q") {

            $query .= " AND NOW()";
         }

         db();
         $res = db_query($query);

         $row2 = array_shift($res);



         if (($row["DT"] * $row["RC"]) > 0) {
            $efficiency = 100 * $row2["P"] / ($row["DT"] * $row["RC"]);
         } else {
            $efficiency = 0;
         }

      ?>
        <?php
         db();
         db_query("INSERT INTO loop_timeclock_leaderboard ( `name` , `totalhours` , `efficiency` ) VALUES ('" . $name . "'," . $hours . ", " . $efficiency . ")");
      }

      ?>
    </table>
    </td>
    <td valign="top">
        <!--------------------- BEGIN QUICK LINKS  ----------------------------------------------->
        <table cellSpacing="1" cellPadding="1" border="0" width="300">
            <tr align="middle">
                <td colSpan="10" class="style7">
                    <b>QUICK LINKS</b>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a href="http://loops.usedcardboardboxes.com/report_timeclock_public.php"><b>View Timeclock</b></a>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a target="_blank"
                        href="report_search_processed_inbound_trailers.php?sorting_warehouse=925"><b>Search Processed
                            Inbound Trailers</b></a>
                </td>
            </tr>
        </table>
        <!----------------------- END QUICK LINKS ------------>
    </td>
    </tr>
    </table>
    <!------------- McCormick Trailer Report ---------------->
    <?php
   if ($_REQUEST["action"] == 'run') {

      $start_date = date('Ymd', $start_date);

      $end_date = date('Ymd', $end_date + 86400);



      if ($start_date > $end_date) {

         echo "<font size=4 color=red>Error: End Date before Start Date</font>";
      }



   ?>
    <table width=1400>
        <tr>
            <td>
                <input type=hidden name="surveyview" value="<?php echo $_REQUEST["surveyview"]; ?>" ?>"?>"?>">
                <input type=hidden name="start_date" value="<?php echo $_REQUEST["start_date"]; ?>" ?>"?>"?>">
                <input type=hidden name="end_date" value="<?php echo $_REQUEST["end_date"]; ?>" ?>"?>"?>">
                <input type=hidden name="action" value="run">
                <table cellSpacing="1" cellPadding="1" width="550" border="0">
                    <tr align="middle">
                        <td colSpan="10" class="style7">
                            <b>McCORMICK TRAILER REPORT</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 150" class="style17" align="center">
                            <b>DATE REQUEST</b></font>
                        </td>
                        <td style="width: 100" class="style17" align="center">
                            <b>TRAILER #</b></font>
                        </td>
                        <td style="width: 50" class="style17" align="center">
                            <b>DOCK</b></font>
                        </td>
                        <td class="style5" style="width: 150" align="center">
                            <b>REQUESTED BY</b>
                        </td>
                        <td align="middle" style="width: 100" class="style16" align="center">
                            <b>VALUE</b>
                        </td>
                        <td align="middle" style="width: 100" class="style16" align="center">
                            <b>STATUS</b>
                        </td>
                    </tr>
                    <?php
                  $query = "SELECT * FROM loop_transaction WHERE warehouse_id = 15 AND";

                  if ($_REQUEST["start_date"] != "") {

                     $query .= " pr_requestdate BETWEEN '" . $_REQUEST["start_date"] . "'";
                  }

                  if ($_REQUEST["end_date"] != "") {

                     $query .= " AND '" . $_REQUEST["end_date"] . "' ORDER BY pr_requestdate DESC";
                  }



                  $grandtotal = 0;

                  db();
                  $res = db_query($query);

                  while ($row = array_shift($res)) {



                  ?>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <a href="http://loops.usedcardboardboxes.com/mccormickdashboard.php?action=run&start_date=<?php echo htmlspecialchars($_REQUEST["start_date"]); ?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&surveyview=<?php echo $_REQUEST["surveyview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1"
                                ?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&surveyview=<?php echo $_REQUEST["surveyview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1"?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&surveyview=<?php echo $_REQUEST["surveyview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1"?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&surveyview=<?php echo $_REQUEST["surveyview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1"><?php echo $row["dt_trailer"]; ?></a>
                        </td>
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php echo $row["pr_dock"]; ?></td>
                        <td bgColor="#e4e4e4" class="style3">
                            <?php echo $row["pr_requestby"]; ?></td>
                        <td bgColor="#e4e4e4" class="style3" align="right">
                            <?php
                           $gbw = 0;

                           $vob = 0;



                           $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_boxes_sort.trans_rec_id = " . $row["id"];

                           db();
                           $dt_view_res = db_query($dt_view_qry);



                           while ($dt_view_row = array_shift($dt_view_res)) {

                              if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {

                                 $gbw += $dt_view_row["bweight"] * $dt_view_row["boxgood"];

                                 $vob += $dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"];
                              }
                           }



                           $voo = 0;



                           $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $row["id"] . " AND loop_boxes.isbox LIKE 'N'";

                           db();
                           $dt_view_res = db_query($dt_view_qry);



                           while ($dt_view_row = array_shift($dt_view_res)) {

                              if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {

                                 $voo += $dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"];
                              }
                           }

                           ?>
                            $<?php
                              $grandtotal += number_format($vob + $voo + $row["othercharge"] + $row["freightcharge"], 2);

                              echo number_format($vob + $voo + $row["othercharge"] + $row["freightcharge"], 2); ?>
                        </td>
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php
                           if ($row["pmt_entered"] != 0) {

                              echo "Paid";
                           } elseif ($row["sort_entered"] == 1) {

                              echo "Sorted";
                           } elseif ($row["pa_employee"] != "") {

                              echo "In Process";
                           } else {

                              echo "Requested";
                           }

                           ?>
                        </td>
                    </tr>
                    <?php
                  }

                  ?>
                    <tr>
                        <td bgColor="#e4e4e4" class="style3" colspan="4" align="right">
                            <b>TOTAL</b></font>
                        </td>
                        <td bgColor="#e4e4e4" class="style3" align="right">
                            <b><?php echo $grandtotal; ?></b></font>
                        </td>
                        <td bgColor="#e4e4e4" class="style3">
                        </td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <?php
         }

         if ($_REQUEST["trailer"] > 0) {

            $dt_view_qry = "SELECT * FROM loop_transaction WHERE id = " . $_REQUEST["trailer"];

            db();
            $dt_view_res = db_query($dt_view_qry);

            $dt_view_trl_row = array_shift($dt_view_res)

            ?>
                <table cellSpacing="1" cellPadding="1" border="0" width="800">
                    <tr align="middle">
                        <td class="style7" colspan="10" style="height: 16px"><strong>SORT REPORT FOR TRAILER
                                #<?php echo $dt_view_trl_row["pr_trailer"]; ?></strong></td>
                    </tr>
                    <tr align="middle">
                        <td bgColor="88EEEE" colspan="10" class="style17"><strong>BOXES</strong></td>
                    </tr>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style12">Good Boxes</td>
                        <td bgColor="#e4e4e4" class="style12">Bad Boxes</td>
                        <td bgColor="#e4e4e4" width="350" class="style12">Description</td>
                        <td bgColor="#e4e4e4" class="style12">Box Weight</td>
                        <td bgColor="#e4e4e4" class="style12">Value Per Box</td>
                        <td bgColor="#e4e4e4" class="style12">Value of Boxes</td>
                    </tr>
                    <?php
                  $gb = 0;

                  $bb = 0;

                  $gbw = 0;

                  $vob = 0;





                  $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_boxes_sort.trans_rec_id = " . $_REQUEST["trailer"];

                  db();
                  $dt_view_res = db_query($dt_view_qry);



                  while ($dt_view_row = array_shift($dt_view_res)) {



                     if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {

                  ?>
                    <tr>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["boxgood"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["boxbad"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12left">
                            <?php echo $dt_view_row["blength"]; ?> <?php echo $dt_view_row["blength_frac"]; ?> x
                            <?php echo $dt_view_row["bwidth"]; ?> <?php echo $dt_view_row["bwidth_frac"]; ?> x
                            <?php echo $dt_view_row["bdepth"]; ?> <?php echo $dt_view_row["bdepth_frac"]; ?>
                            <?php echo $dt_view_row["bdescription"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["bweight"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["sort_boxgoodvalue"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <?php echo number_format($dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"], 2); ?>
                        </td>
                    </tr>
                    <?php
                        $gb += $dt_view_row["boxgood"];

                        $bb += $dt_view_row["boxbad"];

                        $gbw += $dt_view_row["bweight"] * $dt_view_row["boxgood"];

                        $vob += $dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"];
                     }
                  } ?>
                    <tr>
                        <td bgColor="#e4e4e4" class="style12right"><strong><?php echo $gb; ?></strong></td>
                        <td bgColor="#e4e4e4" class="style12right"><strong><?php echo $bb; ?></strong></td>
                        <td bgColor="#e4e4e4" class="style12"><strong>BOX TOTALS</strong></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong><?php echo number_format($gbw, 2); ?></strong>
                        </td>
                        <td bgColor="#e4e4e4" class="style12"> </td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($vob, 2); ?></strong>
                        </td>
                    </tr>
                    <tr align="middle">
                        <td bgColor="88EEEE" colspan="10" class="style17"><strong>OTHER ITEMS</strong></td>
                    </tr>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" colspan="2" class="style12">Quantity</td>
                        <td bgColor="#e4e4e4" class="style12left">Description</td>
                        <td bgColor="#e4e4e4" class="style12right">Units</td>
                        <td bgColor="#e4e4e4" class="style12right">Value Per Unit</td>
                        <td bgColor="#e4e4e4" class="style12right">Total Value</td>
                    </tr>
                    <?php
                  $voo = 0;

                  $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $_REQUEST["trailer"] . " AND loop_boxes.isbox LIKE 'N'";

                  db();
                  $dt_view_res = db_query($dt_view_qry);

                  while ($dt_view_row = array_shift($dt_view_res)) {

                     if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {

                  ?>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="2" class="style12right"><?php echo $dt_view_row["boxgood"]; ?>
                        </td>
                        <td bgColor="#e4e4e4" class="style12left">
                            <?php echo $dt_view_row["bdescription"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["bunit"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <?php echo number_format($dt_view_row["sort_boxgoodvalue"], 3); ?></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <?php echo number_format($dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"], 2); ?>
                        </td>
                    </tr>
                    <?php
                        $voo += $dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"];
                     }
                  } ?>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>OTHER ITEM TOTALS</strong></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($voo, 2); ?></strong>
                        </td>
                    </tr>
                    <tr align="middle">
                        <td bgColor="88EEEE" colspan="10" class="style17"><strong>TOTALS</strong></td>
                    </tr>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>GROSS EARNINGS</strong></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($vob + $voo, 2); ?></strong>
                        </td>
                    </tr>
                    <?php if ($dt_view_trl_row["othercharge"] != 0) { ?>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="5" class="style12right">
                            <strong><?php echo $dt_view_trl_row["otherdetails"]; ?></strong>
                        </td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($dt_view_trl_row["othercharge"], 2); ?></strong>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>FREIGHT</strong></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($dt_view_trl_row["freightcharge"], 2); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>TOTAL EARNED</strong></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($vob + $voo + $dt_view_trl_row["othercharge"] + $dt_view_trl_row["freightcharge"], 2); ?></strong>
                        </td>
                    </tr>
                    <?php } ?>
            </td>
        </tr>
    </table>
</body>

</html>