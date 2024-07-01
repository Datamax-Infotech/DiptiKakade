<?php


session_start();
if ($_REQUEST["no_sess"] == "yes") {
} else {
    //require("inc/header_session.php");
}

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
require("inc/functions_mysqli.php");
require("function-dashboard-newlinks.php");


function get_user_name(int $id): string
{
    db_b2b();
    $sql = db_query("SELECT `name` FROM `employees` WHERE `employeeID` = $id");
    $res = array_shift($sql);
    return $res['name'];
}

function get_status_name(int $id): string
{
    db();
    $sql = db_query("SELECT `ts_name` FROM `task_status` WHERE `id` = $id");
    $res = array_shift($sql);
    return $res['ts_name'];
}


function get_department_name(int $id): string
{
    db();
    $sql = db_query("SELECT `dept_name` FROM `project_dept_master` WHERE `id` = $id");
    $res = array_shift($sql);
    return $res['dept_name'];
}


$eid = $_COOKIE['b2b_id']; // this is the b2b.employees ID number, not the loop_employees ID number
if ($eid == 0) {
    $eid = 35;
}
if ($eid == 22) {
    $eid = 39;
}
if ($eid == 160) {
    $eid = 39;
}
$x = "SELECT * from loop_employees WHERE b2b_id = '" . $eid . "'";
db();
$viewres = db_query($x);
$empr = array_shift($viewres);
$initials = $empr['initials'];
$user_lvl = $empr['level'];
$name = $empr['name'];

if (isset($_POST["submit"]) && $_POST["task_New"] == "ADD") {

    $title = str_replace("'", "\'", $_POST["task_title"]);
    $desc  = str_replace("'", "\'", $_POST["task_desc"]);
    // task_dependency_id
    $insql = "INSERT INTO `task_master`(`task_title`, `task_details`, `task_assignto`,
				`task_duedate`, `task_attached`, `task_attached_id`, `task_dependency`, 
				`task_priority`, `task_stages`, `task_status`, `task_entered_by`) VALUES (
				'" . $title . "', '" . $desc . "', '" . $_POST["assignto"] . "', 
				'" . $_POST["duedate"] . "','" . $_POST["task_type"] . "','" . $_POST["task_type_id"] . "', '" . $_POST["task_dependency"] . "', 
				'" . $_POST["task_priority"] . "', '" . $_POST["task_stage"] . "' , 1, '" . $_COOKIE["b2b_id"] . "')";

    db();
    db_query($insql);
    $insert_record_id = tep_db_insert_id();

    if ($_POST["task_dependency"] == 1) {
        if ($_POST["task_dependency_id"] != "") {
            $insql2 = "INSERT INTO `dependency_master`(`task_id`, `dependent_taskid`) VALUES ('" . $insert_record_id . "', '" . $_POST["task_dependency_id"] . "')";
            db();
            db_query($insql2);
        }
    }
}

if (isset($_POST["submit"]) && $_POST["task_Edit"] == "EDIT") {

    $title = str_replace("'", "\'", $_POST["task_title"]);
    $desc  = str_replace("'", "\'", $_POST["task_desc"]);
    // task_dependency_id
    $insql = "Update `task_master` set `task_title` = '" . $title . "', `task_details` = '" . $desc . "', `task_assignto` = '" . $_POST["assignto"] . "' ,
				`task_duedate` = '" . $_POST["duedate"] . "', `task_attached` = '" . $_POST["task_type"] . "', `task_attached_id` = '" . $_POST["task_type_id"] . "', 
				`task_dependency` = '" . $_POST["task_dependency"] . "', `task_priority` = '" . $_POST["task_priority"] . "', 
				`task_stages` = '" . $_POST["task_stage"] . "', `task_status` = 1 , `task_entered_by` = '" . $_COOKIE["b2b_id"] . "'
				where id = '" . $_POST["id"] . "'";
    db();
    db_query($insql);

    if ($_POST["task_dependency"] == 1) {
        if ($_POST["task_dependency_id"] != "") {
            $insql2 = "delete from `dependency_master` where `task_id` '" . $_POST["id"] . "'";
            db();
            db_query($insql2);

            $insql2 = "INSERT INTO `dependency_master`(`task_id`, `dependent_taskid`) VALUES ('" .  $_POST["id"] . "', '" . $_POST["task_dependency_id"] . "')";
            db();
            db_query($insql2);
        }
    }
}



?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>Task Master</title>

    <style>
    .search_header {
        font-size: 14px;
    }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <LINK rel='stylesheet' type='text/css' href='css/task.css'>
    <LINK rel='stylesheet' type='text/css' href='css/project_entry.css'>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal2 = new CalendarPopup("listdiv");
    cal2.showNavigationDropdowns();
    </script>
    <script>
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    //

    function
    frmcheck() {
        var
            ele =
            document.getElementById("dept_name").value;
        if (ele == "") {
            alert("Please Enter Department Name");
            return false;
        }
        return true;
    }

    function frm_add_task_chk() {
        var
            err_msg =
            "";
        if (document.getElementById("task_title").value ==
            "") {
            err_msg = "Please Enter the Task Title";
        }
        if (document.getElementById("assignto").value ==
            "") {
            err_msg = "Please Select Task Assignto.";
        }
        var
            inst =
            FCKeditorAPI.GetInstance("txtdescription");
        var
            desctext =
            inst.GetHTML();
        document.getElementById("task_desc").value =
            desctext;
        if (err_msg ==
            "") {
            return
            true;
        } else {
            alert(err_msg);
            return
            false;
        }
    }

    function
    frm_edit_task_chk() {
        var
            err_msg =
            "";
        if (document.getElementById("task_title").value ==
            "") {
            err_msg =
                "Please Enter the Task Title ";
        }
        if (document.getElementById("assignto").value == "") {
            err_msg = "Please Select Task Assign to.";
        }
        var
            inst =
            FCKeditorAPI.GetInstance("txtdescription");
        var
            desctext =
            inst.GetHTML();
        document.getElementById("task_desc").value =
            desctext;
        if (err_msg ==
            "") {
            return
            true;
        } else {
            alert(err_msg);
            return
            false;
        }
    }

    function
    view_task_edit(e) {
        document.getElementById("fade").style.display =
            'block';
        document.getElementById("light").style.display =
            'block';
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";


        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else {
            xhr = new ActiveXObject("Mircosoft.XMLHTTP");
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("light").innerHTML = xhr.responseText;

            }
        }

        xhr.open("POST", "manage_task_edit_frm.php?id=" + e, true);
        xhr.send();
    }

    function open_add_task() {
        document.getElementById("fade").style.display = 'block';
        document.getElementById("light").style.display = 'block';

        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("light").innerHTML = xhr.responseText;
            }
        }

        xhr.open("POST", "manage_task_add_frm.php", true);
        xhr.send();
    }
    </script>
</head>

<body>
    <!-- <script type="text/javascript" src="wz_tooltip.js"></script> -->

    <div id="fade" class="black_overlay">
        <div id="light" class="white_content"></div>
    </div>
    <div ID="listdiv"
        STYLE="z-index:999;position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
    </div>

    <?php include("inc/header.php"); ?>
    <div class="container">
        <div class="main_data_css">
            <div class="dashboard_heading" style="float: left;">
                <div style="float: left;">Tasks</div>
                &nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">This place is where all Tasks are processed.</span>
                </div>
                <div class="tooltip" style="float:right;">
                    <input type="button" id="task_icon" class="btn_ttext" onclick="open_add_task()" value="Add Task" />
                </div>
                <div style="height: 13px;">&nbsp;</div>
            </div>
            <br />
            <div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="" id="">

                    <span class="search_header">Assign To:</span>
                    <select id="assignto" name="assignto">
                        <option value="" <?php echo (($_REQUEST["assignto"] == "") ? 'selected' : ''); ?>>All</option>
                        <?php
                        db();
                        $query = db_query("SELECT * FROM loop_employees where status = 'Active' order by name");
                        while ($rowsel_getdata = array_shift($query)) {
                            $tmp_str = "";
                            if (isset($_REQUEST["assignto"])) {
                                if ($_REQUEST["assignto"] != "") {
                                    if ($_REQUEST["assignto"] == $rowsel_getdata["b2b_id"]) {
                                        $tmp_str = " selected ";
                                    }
                                }
                            } else {
                                if ($_COOKIE["b2b_id"] == $rowsel_getdata["b2b_id"]) {
                                    $tmp_str = " selected ";
                                }
                            }
                        ?>
                        <option value="<?php echo $rowsel_getdata["b2b_id"]; ?>" <?php echo $tmp_str; ?>>
                            <?php echo $rowsel_getdata['name']; ?></option>
                        <?php }
                        ?>
                    </select>
                    <input type="submit" id="btnsubmit" name="btnsubmit" value="Search" />
                </form>
            </div>
            <div class="">
                <?php
                if ($_REQUEST["btnsubmit"]) {
                    if ($_REQUEST["assignto"] != "") {
                        db();
                        $sql_main = db_query("SELECT * FROM loop_employees where b2b_id = '" . $_REQUEST["assignto"] . "'");
                    } else {
                        db();
                        $sql_main = db_query("SELECT * FROM loop_employees WHERE status = 'Active' ORDER BY name");
                    }
                } else {
                    db();
                    $sql_main = db_query("SELECT * FROM loop_employees WHERE b2b_id=" . $_COOKIE["b2b_id"]);
                }
                while ($main_row = array_shift($sql_main)) {


                ?>
                <table class="tbl_project" cellSpacing="1" cellPadding="1" border="0">
                    <tr>
                        <th colspan="9" class="headrow">
                            Active Task for "<?php echo $main_row["name"]; ?>"
                        </th>
                    </tr>
                    <tr>
                        <th class="headrow" width="100px">Sr. No</th>
                        <th class="headrow" width="250px">Task Name</th>
                        <th class="headrow" width="350px">Task Description</th>
                        <th class="headrow" width="100px">Assign To</th>
                        <th class="headrow" width="100px">Due Date</th>
                        <th class="headrow" width="100px">Priority</th>
                        <th class="headrow" width="100px">Task Type</th>
                        <th class="headrow" width="100px">Dependency</th>
                        <th class="headrow">Action</th>
                    </tr>
                    <?php
                        $srno = 1;
                        db();
                        $sql1 = db_query("SELECT * FROM task_master where task_status = 1 AND task_assignto = '" . $main_row["b2b_id"] . "' ORDER BY id DESC");

                        while ($r = array_shift($sql1)) {
                            $depcy = "No";
                            if ($r['task_dependency'] == 1) $depcy = "Yes";
                            echo "<tr class='tbl_project'>";
                            echo "<td class=''>" . $srno++ . "</td>";
                            echo "<td class=''>" . $r['task_title'] . "</td>";
                            echo "<td class=''>" . $r['task_details'] . "</td>";
                            echo "<td class=''>" . get_user_name($r['task_assignto']) . "</td>";
                            echo "<td class=''>" . $r['task_duedate'] . "</td>";
                            echo "<td class=''>" . $r['task_priority'] . "</td>";
                            echo "<td class=''>" . $r['task_attached'] . "</td>";
                            echo "<td class=''>" . $depcy . "</td>";
                            echo "<td class=''><input type='button' value='Edit' onclick='view_task_edit(" . $r['id'] . ")'></td>";
                            echo "</tr>";
                        }
                        ?>
                </table>
                <?php } ?>
            </div>
            <div class="">&nbsp;</div>


        </div>
    </div>


    <script>
    function resetctrls() {
        document.getElementById("frmtask").reset();
    }

    function showtask_type(e) {
        var xhr = new XMLHttpRequest();
        if (e != 'Private') {
            document.getElementById("task_type_hide").style.display = "block";
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("task_type_hide").innerHTML = xhr.responseText;
                }
            }

            xhr.open("POST", "manage_task_type_data.php?proc=" + e, true);
            xhr.send();

        } else {
            document.getElementById("task_type_hide").style.display = "none";
        }

    }

    function showtask_dependency(e) {
        var xhr = new XMLHttpRequest();
        if (e == 1) {
            document.getElementById("task_depd_hide").style.display = "block";
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("task_depd_hide").innerHTML = xhr.responseText;
                }
            }

            xhr.open("POST", "manage_task_type_data.php?proc=Dependency", true);
            xhr.send();

        } else {
            document.getElementById("task_depd_hide").style.display = "none";
        }

    }

    var modal = document.getElementById("fade");
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>

</body>

</html>