<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

?>
<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

<head>

    <style type="text/css">
    pre {
        /* height: 200px; */
        width: 380px;
        overflow: auto;
        font-size: 8pt;
        text-align: left;
        overflow-x: auto;
        /* Use horizontal scroller if needed; for Firefox 2, 
		notwhite-space: pre-wrap;	/* css-3 */
        white-space: -moz- pre-wrap !important;
        /* Mozilla, since 1999 */
        word-wrap: break-word;
        /* Internet Explorer 5.5+ */
        margin: 0px 0px 0px 0px;
        padding: 5px 5px 3px 5px;
        white-space: normal;
        /* crucial for IE 6, maybe 7? */
    }

    .black_overlay {
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: gray;
        z-index: 1001;
        -moz-opacity: 0.8;
        opacity: .80;
        filter: alpha(opacity=80);
    }

    .white_content {
        display: none;
        position: absolute;
        top: 5%;
        left: 10%;
        width: 60%;
        height: 90%;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
    }

    .white_content_details {
        display: none;
        position: absolute;
        top: 0%;
        left: 10%;
        width: 50%;
        height: auto;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
        box-shadow: 8px 8px 5px #888888;
    }
    </style>

    <script>
    function displayemail(id) {
        document.getElementById("light").innerHTML = document.getElementById("emlmsg" + id).innerHTML;
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';
    }
    </script>

</head>

<body>

    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>

    <h2>CRM List</h2>
    <form method="post" name="emp_quota_yr" action="report_daily_chart_crm_list.php">
        <table width="1000" border="0" cellspacing="1" cellpadding="1">
            <tr>
                <td width="5%" bgcolor="#C0CDDA">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">Sr No</font>
                </td>
                <td width="20%" bgcolor="#C0CDDA">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">Company Name</font>
                </td>
                <td width="6%" align="center" bgcolor="#C0CDDA">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">CRM Type</font>
                </td>
                <td width="44%" bgcolor="#C0CDDA">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">CRM Message</font>
                </td>
                <td width="10%" bgcolor="#C0CDDA">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">Employee</font>
                </td>
                <td width="10%" bgcolor="#C0CDDA">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">View File</font>
                </td>
                <td width="10%" bgcolor="#C0CDDA">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">CRM Date</font>
                </td>
            </tr>
            <?php
            db();
            $eml = "";
            $result_crm = db_query("Select email from loop_employees where initials = '" . $_REQUEST["crmemp"] . "'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $eml = $rowemp_crm["email"];
            }

            $emp_initials_list = '';
            $emp_b2bid_list = '';
            $emp_initials_list_str = '';
            $emp_b2bid_list_str = '';
            $emp_eml_list_str = '';
            if ($_REQUEST["other_flg"] == "yes") {
                db();
                // and dashboard_view = 'Sales'
                if ($_REQUEST["purchasing"] == "yes") {
                    $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE activity_tracker_flg_purchasing = 1 and status = 'Active'";
                } else {
                    $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE activity_tracker_flg = 1  and status = 'Active'";
                }
                $result = db_query($sql);
                $emp_eml_list = $emp_eml_list ?? "";
                while ($rowemp = array_shift($result)) {
                    $emp_initials_list .= "'" . $rowemp["initials"] . "',";
                    $emp_b2bid_list .= "'" . $rowemp["b2b_id"] . "',";
                    $emp_eml_list .= "'" . $rowemp["email"] . "',";
                }
                $emp_initials_list = rtrim($emp_initials_list, ",");
                $emp_b2bid_list = rtrim($emp_b2bid_list, ",");
                $emp_eml_list = rtrim($emp_eml_list, ",");

                $emp_initials_list_str = " not in (" . $emp_initials_list . ")";
                $emp_b2bid_list_str = " not in (" . $emp_b2bid_list . ")";
                $emp_eml_list_str = " not in (" . $emp_eml_list . ")";
            }

            db_email();
            $crm_numberof_chr = 500;
            $crm_rows_per_page = 25;
            $crm_numberof_chr_divheight = 350;
            $sql_var = "select * from tblvariable";
            $result = db_query($sql_var);
            while ($myrowsel = array_shift($result)) {
                if (strtoupper($myrowsel["variablename"]) == strtoupper("crm_numberof_chr")) {
                    $crm_numberof_chr = $myrowsel["variablevalue"];
                }
                if (strtoupper($myrowsel["variablename"]) == strtoupper("crm_rows_per_page")) {
                    $crm_rows_per_page = $myrowsel["variablevalue"];
                }
                if (strtoupper($myrowsel["variablename"]) == strtoupper("crm_numberof_chr_divheight")) {
                    $crm_numberof_chr_divheight = $myrowsel["variablevalue"];
                }
            }

            $flg_ph = "('email')";
            if ($_REQUEST["phone"] == "y") {
                $flg_ph = "('phone')";
            }

            if ($_REQUEST["CRMday"] == "T") {
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate >= CURDATE() and fromadd $emp_eml_list_str");
                    } else {
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate = '" . $_REQUEST["date_from_val"] . "' and fromadd $emp_eml_list_str ");
                    }
                    $eml_list = "";
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $eml_list .= $rowemp_crm["unqid"] . ",";
                    }
                    if ($eml_list != "") {
                        $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            if ($_REQUEST["phone"] == "y") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND  timestamp >= CURDATE() ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND  timestamp >= CURDATE() ) ORDER BY ID DESC";
                            }
                        } else {
                            if ($_REQUEST["phone"] == "y") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND  timestamp = " . $_REQUEST["date_from_val"] . " ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND  timestamp = " . $_REQUEST["date_from_val"] . ") ORDER BY ID DESC";
                            }
                        }
                    } else {
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND  timestamp >= CURDATE()";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND  timestamp = " . $_REQUEST["date_from_val"] . "";
                        }
                    }
                } else {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate >= CURDATE() and fromadd = '" . $eml . "'");
                    } else {
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate = '" . $_REQUEST["date_from_val"] . "' and fromadd = '" . $eml . "'");
                    }
                    $eml_list = "";
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $eml_list .= $rowemp_crm["unqid"] . ",";
                    }
                    if ($eml_list != "") {
                        $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            if ($_REQUEST["phone"] == "y") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND  timestamp >= CURDATE() ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND  timestamp >= CURDATE() ) ORDER BY ID DESC";
                            }
                        } else {
                            if ($_REQUEST["phone"] == "y") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND  timestamp = " . $_REQUEST["date_from_val"] . " ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND  timestamp = " . $_REQUEST["date_from_val"] . ") ORDER BY ID DESC";
                            }
                        }
                    } else {
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND  timestamp >= CURDATE()";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND  timestamp = " . $_REQUEST["date_from_val"] . "";
                        }
                    }
                }
            }

            if ($_REQUEST["CRMday"] == "b2bl") {
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["phone"] == "y") {
                        $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and $emp_initials_list_str AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY ID DESC";
                    } else {
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' and fromadd $emp_eml_list_str");
                        $eml_list = "";
                        while ($rowemp_crm = array_shift($result_crm)) {
                            $eml_list .= $rowemp_crm["unqid"] . ",";
                        }
                        if ($eml_list != "") {
                            $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and $emp_initials_list_str AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "') ORDER BY ID DESC";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and $emp_initials_list_str AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "'";
                        }
                    }
                } else {
                    if ($_REQUEST["phone"] == "y") {
                        $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY ID DESC";
                    } else {

                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' and fromadd = '" . $eml . "'");
                        $eml_list = "";
                        while ($rowemp_crm = array_shift($result_crm)) {
                            $eml_list .= $rowemp_crm["unqid"] . ",";
                        }
                        if ($eml_list != "") {
                            $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "') ORDER BY ID DESC";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "'";
                        }
                    }
                }
            }

            if ($_REQUEST["CRMday"] == "poenter") {
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["phone"] == "y") {
                        $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 23:59:59", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY ID DESC";
                    } else {
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' and fromadd $emp_eml_list_str");
                        $eml_list = "";
                        while ($rowemp_crm = array_shift($result_crm)) {
                            $eml_list .= $rowemp_crm["unqid"] . ",";
                        }
                        if ($eml_list != "") {
                            $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 23:59:59", strtotime($_REQUEST["date_to_val"])) . "') ORDER BY ID DESC";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 23:59:59", strtotime($_REQUEST["date_to_val"])) . "'";
                        }
                    }
                } else {
                    if ($_REQUEST["phone"] == "y") {
                        $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 23:59:59", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY ID DESC";
                    } else {
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' and fromadd = '" . $eml . "'");
                        $eml_list = "";
                        while ($rowemp_crm = array_shift($result_crm)) {
                            $eml_list .= $rowemp_crm["unqid"] . ",";
                        }
                        if ($eml_list != "") {
                            $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 23:59:59", strtotime($_REQUEST["date_to_val"])) . "') ORDER BY ID DESC";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 23:59:59", strtotime($_REQUEST["date_to_val"])) . "'";
                        }
                    }
                }
            }

            if ($_REQUEST["CRMday"] == "activity_tracker_daily_averages_daily_touch") {
                if ($_REQUEST["other_flg"] == "yes") {

                    db_email();
                    $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' and fromadd $emp_eml_list_str");
                } else {

                    db_email();
                    $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' and fromadd = '" . $eml . "'");
                }
                $eml_list = "";
                while ($rowemp_crm = array_shift($result_crm)) {
                    $eml_list .= $rowemp_crm["unqid"] . ",";
                }
                if ($eml_list != "") {
                    $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                    $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in ('phone', 'email') and EMPLOYEE $emp_initials_list_str AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 23:59:59", strtotime($_REQUEST["date_to_val"])) . "') ORDER BY ID DESC";
                } else {
                    $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in ('phone', 'email') and EMPLOYEE $emp_initials_list_str AND timestamp between '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 23:59:59", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY ID DESC";
                }
            }

            if ($_REQUEST["CRMday"] == "Y") {
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["in_dt_range"] != "yes") {

                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and fromadd $emp_eml_list_str");
                    } else {

                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . " and fromadd $emp_eml_list_str");
                    }
                    $eml_list = "";
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $eml_list .= $rowemp_crm["unqid"] . ",";
                    }
                    if ($eml_list != "") {
                        $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            if ($_REQUEST["phone"] == "y") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND ) ORDER BY ID DESC";
                            }
                        } else {
                            if ($_REQUEST["phone"] == "y") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . " ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . ") ORDER BY ID DESC";
                            }
                        }
                    } else {
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "";
                        }
                    }
                } else {
                    if ($_REQUEST["in_dt_range"] != "yes") {

                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and fromadd = '" . $eml . "'");
                    } else {

                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . " and fromadd = '" . $eml . "'");
                    }
                    $eml_list = "";
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $eml_list .= $rowemp_crm["unqid"] . ",";
                    }
                    if ($eml_list != "") {
                        $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            if ($_REQUEST["phone"] == "y") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND ) ORDER BY ID DESC";
                            }
                        } else {
                            if ($_REQUEST["phone"] == "y") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . " ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . ") ORDER BY ID DESC";
                            }
                        }
                    } else {
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "";
                        }
                    }
                }
            }

            if ($_REQUEST["CRMday"] == "7") {
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and fromadd $emp_eml_list_str");
                    } else {

                        db_email();
                        $date_from_val = $date_from_val ?? "";
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd $emp_eml_list_str");
                    }
                    $eml_list = "";
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $eml_list .= $rowemp_crm["unqid"] . ",";
                    }
                    if ($eml_list != "") {
                        $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                        if ($_REQUEST["phone"] == "y") {
                            if ($_REQUEST["in_dt_range"] != "yes") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . " ORDER BY ID DESC";
                            }
                        } else {
                            if ($_REQUEST["in_dt_range"] != "yes") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() ) ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . ") ORDER BY ID DESC";
                            }
                        }
                    } else {
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE()";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "";
                        }
                    }
                } else {
                    if ($_REQUEST["in_dt_range"] != "yes") {

                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and fromadd = '" . $eml . "'");
                    } else {
                        $date_from_val = $date_from_val ?? "";
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd = '" . $eml . "'");
                    }
                    $eml_list = "";
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $eml_list .= $rowemp_crm["unqid"] . ",";
                    }
                    if ($eml_list != "") {
                        $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                        if ($_REQUEST["phone"] == "y") {
                            if ($_REQUEST["in_dt_range"] != "yes") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . " ORDER BY ID DESC";
                            }
                        } else {
                            if ($_REQUEST["in_dt_range"] != "yes") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() ) ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . ") ORDER BY ID DESC";
                            }
                        }
                    } else {
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE()";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "";
                        }
                    }
                }
            }

            if ($_REQUEST["CRMday"] == "30") {
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["in_dt_range"] != "yes") {

                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and fromadd $emp_eml_list_str");
                    } else {
                        $date_from_val = $date_from_val ?? "";
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd $emp_eml_list_str");
                    }

                    $eml_list = "";
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $eml_list .= $rowemp_crm["unqid"] . ",";
                    }
                    if ($eml_list != "") {
                        $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                        if ($_REQUEST["phone"] == "y") {
                            if ($_REQUEST["in_dt_range"] != "yes") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . " ORDER BY ID DESC";
                            }
                        } else {
                            if ($_REQUEST["in_dt_range"] != "yes") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() ) ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . ") ORDER BY ID DESC";
                            }
                        }
                    } else {
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE()";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE $emp_initials_list_str AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "";
                        }
                    }
                } else {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and fromadd = '" . $eml . "'");
                    } else {
                        $date_from_val = $date_from_val ?? "";
                        db_email();
                        $result_crm = db_query("Select unqid from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd = '" . $eml . "'");
                    }

                    $eml_list = "";
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $eml_list .= $rowemp_crm["unqid"] . ",";
                    }
                    if ($eml_list != "") {
                        $eml_list = substr($eml_list, 0, strrchr($eml_list, ',') - 1);
                        if ($_REQUEST["phone"] == "y") {
                            if ($_REQUEST["in_dt_range"] != "yes") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . " ORDER BY ID DESC";
                            }
                        } else {
                            if ($_REQUEST["in_dt_range"] != "yes") {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() ) ORDER BY ID DESC";
                            } else {
                                $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and EmailID in (" . $eml_list . ") group by EmailID union (SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . ") ORDER BY ID DESC";
                            }
                        }
                    } else {
                        if ($_REQUEST["in_dt_range"] != "yes") {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE()";
                        } else {
                            $sql = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and  type in $flg_ph and EMPLOYEE LIKE '" . $_REQUEST["crmemp"] . "' AND timestamp " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "";
                        }
                    }
                }
            }

            $cnt = 0; //echo $sql;
            db_b2b();
            $sql = $sql ?? "";
            $dt_view_res2 = db_query($sql);
            while ($crm = array_shift($dt_view_res2)) {
                $cnt = $cnt + 1;
                $comp_name = "";
                db_b2b();
                $dt_view_res3 = db_query("Select nickname, company from companyInfo where ID = " . $crm["companyID"]);
                while ($crm_3 = array_shift($dt_view_res3)) {
                    if ($crm_3["nickname"] != "") {
                        $comp_name = $crm_3["nickname"];
                    } else {
                        $comp_name = $crm_3["company"];
                    }
                }
            ?>

            <tr valign="top">
                <td width="5%" align="center" bgcolor="#E4E4E4">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">
                        <?php echo $cnt; ?>
                    </font>
                </td>
                <td width="5%" align="center" bgcolor="#E4E4E4">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">
                        <a target="_blank"
                            href="viewCompany.php?ID=<?php echo $crm["companyID"]; ?>"><?php echo $comp_name; ?></a>
                    </font>
                </td>
                <td width="6%" align="center" bgcolor="#E4E4E4">
                    <font color="#333333" face="Arial, Helvetica, sans-serif" size="1">
                        <?php if ($crm["type"] == "email") {        ?>
                        <img src="images/email.jpg" width="10" height="8">
                        <?php     } elseif ($crm["type"] == "fax") { ?>
                        <img src="images/fax.jpg" width="13" height="12">
                        <?php     } elseif ($crm["type"] == "phone") { ?>
                        <img src="images/phone.jpg" width="16" height="15">
                        <?php     } elseif ($crm["type"] == "meeting") { ?>
                        <img src="images/meeting.jpg" width="17" height="15">
                        <?php     } elseif ($crm["type"] == "note") {    ?>
                        <img src="images/note.jpg" width="15" height="12">
                        <?php } ?>
                    </font>
                </td>

                <td width="59%" bgcolor="#E4E4E4">
                    <?php

                        $final_msg = "";
                        $final_msg_top = "";
                        $attachment_str = "";
                        $email_body_toppart = "";
                        $attstr = "";
                        if ($crm["EmailID"] != "") {
                            $query = "select emaildate, fromadd, toadd, ccadd, subject FROM tblemail WHERE unqid =" . $crm["EmailID"];
                            db_email();
                            $dt_view_eml = db_query($query);
                            while ($rec_em = array_shift($dt_view_eml)) {
                                $query_att = "select attachmentname FROM tblemail_attachment WHERE emailid =" . $crm["EmailID"];
                                db_email();
                                $dt_view_eml_att = db_query($query_att);
                                while ($rec_em_att = array_shift($dt_view_eml_att)) {
                                    $attachment_str = $attachment_str . "<a style='color:#0000FF' target='_blank' href='emailatt_uploads/" . $crm["EmailID"] . "/" . $rec_em_att["attachmentname"] . "'>" . $rec_em_att["attachmentname"] . "</a>, ";
                                }

                                $final_msg = "";

                                $query_att = "select body_txt FROM tblemail_body_txt WHERE email_id =" . $crm["EmailID"];
                                db_email();
                                $dt_view_eml_att = db_query($query_att);
                                while ($rec_em_att = array_shift($dt_view_eml_att)) {
                                    $final_msg = $rec_em_att["body_txt"];
                                }

                                $final_msg_top = preg_replace("/background-color:/", "\ ", $final_msg);

                                $email_body_toppart = "<b>" . $rec_em["subject"] . "</b> <br/> Date: " . date("m/d/Y h:i:s a", strtotime($rec_em["emaildate"])) . "<br/> From:" . $rec_em["fromadd"] . "<br/>";
                                $email_body_toppart .= "To: " . $rec_em["toadd"];
                                if ($rec_em["ccadd"] != "") {
                                    $email_body_toppart .= "<br/>Cc: " . $rec_em["ccadd"];
                                }
                                $email_body_toppart .= "<div style='height:1px; background: url(images/singleline.png) repeat-x;'></div>";

                                if (trim($attachment_str) == "") {
                                    $attstr = "";
                                } else {
                                    $attstr = 'Attachment: ' . substr($attachment_str, 0, strlen(trim($attachment_str)) - 1) . "<br/><br/>";
                                }
                            }
                        } else {

                            $final_msg = "";
                            if (!is_null($crm["email_tmpl_id"])) {
                                if ($crm["email_tmpl_id"] == 5) {
                                    $query = "SELECT * FROM emailtext WHERE email_id=" . $crm["email_tmpl_id"];
                                    db_b2b();
                                    $dt_view_res3 = db_query($query);
                                    while ($em = array_shift($dt_view_res3)) {
                                        $final_msg = stripslashes($em["email_name"]) . "<br>";
                                    }
                                }
                            }
                            $final_msg .= nl2br(stripslashes(stripslashes(str_replace("<a", "<a target='_blank'", $crm["message"]))));

                            if (!is_null($crm["email_tmpl_id"])) {
                                if ($crm["email_tmpl_id"] != 5) {
                                    $query = "SELECT * FROM emailtext WHERE email_id=" . $crm["email_tmpl_id"];
                                    db_b2b();
                                    $dt_view_res4 = db_query($query);
                                    while ($em = array_shift($dt_view_res4)) {
                                        $final_msg .= "&nbsp;" . stripslashes($em["email_name"]);
                                    }
                                }
                            }

                            $final_msg_top = $final_msg;
                            //echo $crm["message"];
                        }
                        ?> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
                        <?php
                            $final_msg_nodivs = strip_tags($final_msg);

                            $tmppos = strlen($email_body_toppart . $attstr . $final_msg_nodivs);
                            if ($tmppos > $crm_numberof_chr) {
                                $tmpstr = "<div style='background-color:#E4E4E4; height:" . $crm_numberof_chr_divheight . "px; width:400px; overflow-x: hidden; overflow-y: hidden;'>" . $email_body_toppart . $attstr . $final_msg_top . "</div> <br/><a href='#' onclick='displayemail(" . $crm["ID"] . ")'>View Complete Email</a> <br/><br/>";
                                $tmpstr .= "<div style='display:none;' id='emlmsg" . $crm["ID"] . "'> <a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close Window</a> <br/><br/>";
                                $tmpstr .= $email_body_toppart . $attstr . $final_msg . "</div>";

                                echo $tmpstr;
                            } else {
                                echo $email_body_toppart . $attstr . $final_msg;
                            }


                            ?>

                    </font>
                </td>
                <td width="10%" align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $crm["employee"] ?>
                    </font>
                </td>
                <td width="10%" align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php if ($crm["file_name"] != "") { ?>
                        <a style="color:#0000FF;" target="_blank" href="crm_files/<?php echo $crm["file_name"] ?>">View
                            File</a><?php } ?>
                    </font>
                </td>
                <td width="15%" align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo str_replace("<a", "<a target='_blank'", $crm["messageDate"]) ?></font>
                </td>
            </tr>
            <?php

            }

            ?>
        </table>

    </form>
</body>

</html>