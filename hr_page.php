<?php
require("inc/header_session.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
?>

<!DOCTYPE HTML>

<html>

<head>
    <title>HR</title>

    <link rel="stylesheet" href="sorter/style_rep.css" />
    <style type="text/css">
        .txtstyle_color {
            font-family: arial;
            font-size: 12;
            height: 16px;
            background: #ABC5DF;
        }

        .txtstyle {
            font-family: arial;
            font-size: 12;
        }
    </style>

    <script LANGUAGE="JavaScript">
        function chkssn() {
            if (document.getElementById('worker').value != 0) {
                if (document.getElementById('hr_comments').value.trim() == "") {
                    alert("Please enter the Comments for HR.");
                    return false;
                } else if (document.getElementById('txtpin').value.trim() == "") {
                    alert("Please enter the last four digit of your SSN.");
                    return false;
                } else {
                    return true;
                }
            }
        }
    </script>

</head>

<body>
    <?php
    $user_initials = $_COOKIE['userinitials'];
    $emp_login_msg = "";
    if (isset($_POST["txtpin"])) {
        $rec_bypass = "no";
        $username = "";
        $sql_chk = "select user_pwd, name from loop_workers where id = " . $_REQUEST["worker"];
        db();
        $result_chk = db_query($sql_chk);
        $rec_found = "notfound";
        $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
        while ($row_chk = array_shift($result_chk)) {
            $rec_found = "found";
            $username = $row_chk["name"];
            if ($row_chk["user_pwd"] != "0") {
                if ($row_chk["user_pwd"] != $_REQUEST["txtpin"]) {
                    $rec_bypass = "err";
                    $emp_login_msg = "<font color=red><b>Entered last four digit of your SSN is not matching, please check.</b></font>";
                } else {
                    $emp_login_msg = "";
                }
            } else {
                $rec_bypass = "err";
                $emp_login_msg = "<font color=red><b>SSN not updated in the master, please check.</b></font>";
            }
        }
    }

    if ($_REQUEST["worker"] == "0") {
        $rec_bypass = "no";
        $emp_login_msg = "";
    }

    if (isset($rec_bypass) == "no") {
        $str_email = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td><p align=\"center\"><a href=\"http://www.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";
        $str_email .= "Comments for HR is as follows:<br><br>";
        $str_email .= $_POST["hr_comments"] . "<br>";
        $str_email .= "</font></td></tr><tr><td><p align=\"center\"><img width=\"650\" height=\"87\" src=\"https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr></table></body></html>";

        $recipient = "HR@UsedCardboardBoxes.com";
        //$recipient = "prasad@extractinfo.com";
        $subject = "Comments for HR from " . isset($username);
        $mailheadersadmin = "From: UsedCardboardBoxes.com <operations@UsedCardboardBoxes.com>\n";
        $mailheadersadmin .= "MIME-Version: 1.0\r\n";
        $mailheadersadmin .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        //mail($recipient,$subject,$str_email,$mailheadersadmin);

        $resp = sendemail_php_function(null, '', $recipient, "", "", "ucbemail@usedcardboardboxes.com", "Admin Usedcardboardboxes", "hr@usedcardboardboxes.com", $subject, $str_email);

        echo "<h3>Email has been send to HR.</h3>";
    }

    $onlyshow_timeclock_str = "";
    if ($_REQUEST["onlyshow_timeclock"] == "yes") {
        $onlyshow_timeclock_str = "?onlyshow_timeclock=yes";
    }
    ?>
    <br />
    <?php if ($_REQUEST["warehouse_id"] == 18) { ?>
        <p style="font-size:10pt;"><a href='huntvalleywarehouse_159265234358979.php<?php echo $onlyshow_timeclock_str; ?>'>Back to HV Dashboard</a>
        </p>
    <?php } ?>
    <?php if ($_REQUEST["warehouse_id"] == 556) { ?>
        <p style="font-size:10pt;"><a href='hannibalwarehouse_141592653.php<?php echo $onlyshow_timeclock_str; ?>'>Back to HA
                Dashboard</a></p>
    <?php } ?>

    <?php if ($_REQUEST["warehouse_id"] == 2563) { ?>
        <p style="font-size:10pt;"><a href='milwaukeeywarehouse_14159265358979.php<?php echo $onlyshow_timeclock_str; ?>'>Back to ML Dashboard</a>
        </p>
    <?php } ?>
    <?php if ($_REQUEST["warehouse_id"] == 15) { ?>
        <p style="font-size:10pt;"><a href='mccormickdashboard_76345679315467990452.php<?php echo $onlyshow_timeclock_str; ?>'>Back to Mccormick
                Dashboard</a></p>
    <?php } ?>
    <?php if ($_REQUEST["warehouse_id"] == 1115) { ?>
        <p style="font-size:10pt;"><a href='hktranswarehouse_1223644451.php<?php echo $onlyshow_timeclock_str; ?>'>Back to HK
                Trans Dashboard</a></p>
    <?php } ?>
    <?php if ($_REQUEST["warehouse_id"] == 5174) { ?>
        <p style="font-size:10pt;"><a href='hktranswarehouse_1223644451.php<?php echo $onlyshow_timeclock_str; ?>'>Back to
                CRC Trans Dashboard</a></p>
    <?php } ?>
    <?php if ($_REQUEST["warehouse_id"] == 4880) { ?>
        <p style="font-size:10pt;"><a href='mccormick_mlcdashboard.php<?php echo $onlyshow_timeclock_str; ?>'>Back to
                Mccormick MLC Dashboard</a></p>
    <?php } ?>


    <table border="0">
        <tr>
            <td colspan="5" align="center" style="font-size:16pt;"><strong>Write To HR</strong></td>
        </tr>
        <tr>
            <td colspan="5" align="left">
                <form method="post" name="daily_log" action="hr_page.php" onsubmit="return chkssn();">
                    <table border="0">
                        <tr>
                            <td>Select Employee:</td>
                            <td><select id="worker" name="worker">
                                    <option value="0">Anonymous</option>
                                    <?php
                                    $query = " SELECT * FROM loop_workers WHERE warehouse_id = " . $_REQUEST["warehouse_id"] . " and active = 1 ";
                                    $query .= " ORDER BY name ASC";
                                    db();
                                    $res = db_query($query);
                                    while ($row = array_shift($res)) {
                                        $sel = "";
                                        if ($_REQUEST["worker"] == $row["id"]) {
                                            $sel = " selected ";
                                        }
                                    ?>
                                        <option value="<?php echo $row["id"] ?>" <?php echo $sel; ?>><?php echo $row["name"] ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Enter Details:</td>
                            <td>
                                <textarea name="hr_comments" id="hr_comments" cols="40" rows="4"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>SSN (last 4 digit):</td>
                            <td>
                                <input type="password" name="txtpin" id="txtpin" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php echo $emp_login_msg; ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" align="center">
                                <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>">
                                <input type="hidden" name="onlyshow_timeclock" id="onlyshow_timeclock" value="<?php echo $_REQUEST["onlyshow_timeclock"]; ?>">
                                <input type="submit" name="btnsave" value="Write To HR">
                                <br><br>
                                If you'd rather leave a voicemail for HR, dial <b>323.724.2500 x797</b>
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>

</body>

</html>