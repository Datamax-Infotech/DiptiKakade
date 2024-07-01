<?php


session_start();
if ($_REQUEST["no_sess"] == "yes") {
} else {
    //require ("inc/header_session.php");
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

if (isset($_POST["submit"]) && $_POST["project_new"] == "ADD") {
    $filetype = "jpg,jpeg,gif,png,PNG,JPG,JPEG,pdf,PDF";
    $allow_ext = explode(",", $filetype);
    /*
		if(!empty( $_FILES['uploadscanrep'] ) ){
			$upload_file = "";	
			
			$tmpName = $_FILES['uploadscanrep']['tmp_name'];
			$ext = pathinfo($_FILES["uploadscanrep"]["name"], PATHINFO_EXTENSION);
			if(in_array(strtolower($ext), $allow_ext) ) {				
				$upload_file = date("Y-m-d hms") ."_". $_FILES['uploadscanrep']['name']; 	
				move_uploaded_file( $tmpName, "water_scanreport/". $upload_file); // move to new location perhaps?
			}
		}	
		*/
    if (!empty($_FILES['uploadscanrep'])) {
        $scan_rep_name = "";
        foreach ($_FILES['uploadscanrep']['tmp_name'] as $index => $tmpName) {

            if (!empty($_FILES['uploadscanrep']['error'][$index])) {
            } else {

                if (!empty($tmpName) && is_uploaded_file($tmpName)) {

                    $ext = pathinfo($_FILES["uploadscanrep"]["name"][$index], PATHINFO_EXTENSION);
                    if (in_array(strtolower($ext), $allow_ext)) {
                        $attachfile_nm_tmp = date("Y-m-d hms") . "_" . preg_replace("/'/", "\'", $_FILES['uploadscanrep']['name'][$index]);

                        $scan_rep_name = $scan_rep_name . $attachfile_nm_tmp . "|";

                        move_uploaded_file($tmpName, "water_scanreport/" . $attachfile_nm_tmp); // move to new location perhaps?
                    }
                }
            }
        }

        $tmppos_1 = strpos($scan_rep_name, "|");
        if ($tmppos_1 != false) {
            if ($scan_rep_name != "") {
                $scan_rep_name = $scan_rep_name . "|" . substr($scan_rep_name, 0, strlen($scan_rep_name) - 1);
            } else {
                $scan_rep_name = substr($scan_rep_name, 0, strlen($scan_rep_name) - 1);
            }
        }
    }


    db();
    $insql = "INSERT INTO `project_master`(`project_name`, `project_description`,
				`project_owner`, `project_dept_id`, `project_deadline`, 
				`project_priority_id`, `project_status_id`, `project_file`,
				`project_enter_by`) VALUES ('" . str_replace("'", "\'", $_POST["project_title"]) . "', '" . str_replace("'", "\'", $_POST["project_desc"]) . "', 
				'" . $_POST["owner_id"] . "','" . $_POST["dept_id"] . "','" . $_POST["deadline"] . "','" . $_POST["priority_id"] . "','" . $_POST["pstatus_id"] . "','" . isset($scan_rep_name) . "','" . $_COOKIE["b2b_id"] . "')";
    db();

    db_query($insql);
}

if ($_POST["project_edit"] == "yes") {
    $filetype = "jpg,jpeg,gif,png,PNG,JPG,JPEG,pdf,PDF";
    $allow_ext = explode(",", $filetype);
    /*
		if(!empty( $_FILES['uploadscanrep'] ) ){
			$upload_file = "";	
			
			$tmpName = $_FILES['uploadscanrep']['tmp_name'];
			$ext = pathinfo($_FILES["uploadscanrep"]["name"], PATHINFO_EXTENSION);
			if(in_array(strtolower($ext), $allow_ext) ) {				
				$upload_file = date("Y-m-d hms") ."_". $_FILES['uploadscanrep']['name']; 	
				move_uploaded_file( $tmpName, "water_scanreport/". $upload_file); // move to new location perhaps?
			}
		}	
		*/
    if (!empty($_FILES['uploadscanrep'])) {
        $scan_rep_name = "";
        foreach ($_FILES['uploadscanrep']['tmp_name'] as $index => $tmpName) {

            if (!empty($_FILES['uploadscanrep']['error'][$index])) {
            } else {

                if (!empty($tmpName) && is_uploaded_file($tmpName)) {

                    $ext = pathinfo($_FILES["uploadscanrep"]["name"][$index], PATHINFO_EXTENSION);
                    if (in_array(strtolower($ext), $allow_ext)) {
                        $attachfile_nm_tmp = date("Y-m-d hms") . "_" . preg_replace("/'/", "\'", $_FILES['uploadscanrep']['name'][$index]);

                        $scan_rep_name = $scan_rep_name . $attachfile_nm_tmp . "|";

                        move_uploaded_file($tmpName, "water_scanreport/" . $attachfile_nm_tmp); // move to new location perhaps?
                    }
                }
            }
        }

        $tmppos_1 = strpos($scan_rep_name, "|");
        if ($tmppos_1 != false) {
            if ($scan_rep_name != "") {
                $scan_rep_name = $scan_rep_name . "|" . substr($scan_rep_name, 0, strlen($scan_rep_name) - 1);
            } else {
                // $scan_rep_name = substr($scan_rep_name, 0, strlen($scan_rep_name, '|') - 1);
                $scan_rep_name = substr($scan_rep_name, 0, strlen($scan_rep_name) - 1);
            }
        }
    }

    db();

    $insql = "Update `project_master` set `project_name` = '" . str_replace("'", "\'", $_POST["project_title"]) . "', `project_description` = '" . str_replace("'", "\'", $_POST["project_desc"]) . "',
				`project_owner` = '" . $_POST["owner_id"] . "', `project_dept_id` = '" . $_POST["dept_id"] . "', `project_deadline` = '" . $_POST["deadline1"] . "', 
				`project_priority_id` = '" . $_POST["priority_id"] . "', `project_status_id` = '" . $_POST["pstatus_id"] . "', `project_file` = '" . isset($scan_rep_name) . "' 
				where project_id = '" . $_POST["project_id"] . "'";
    db();
    db_query($insql);
}

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>Project Master</title>

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

    function frmcheck() {
        var ele = document.getElementById("dept_name").value;
        if (ele == "") {
            alert("Please Enter Department Name");
            return false;
        }
        return true;
    }

    function
    show_add_department() {
        var
            ele =
            document.getElementById("add_department");
        if (ele.style.display ==
            'none') {
            ele.style.display =
                'block';
        } else {
            ele.style.display =
                'none';
        }
    }

    function GetFileSize() {
        var fi = document.getElementById('uploadscanrep'); // GET THE FILE INPUT.

        if (fi.files.length > 0) {

            for (var i = 0; i <= fi.files.length - 1; i++) {
                var filenm = fi.files.item(i).name;

                if (filenm.indexOf("#") > 0) {
                    alert("Remove # from Scan file and then upload file!");
                    document.getElementById("uploadscanrep").value = "";
                }
                if (filenm.indexOf("\'") > 0) {
                    alert("Remove '\'' from Scan file " + filenm + " and then upload file!");
                    document.getElementById("uploadscanrep").value = "";
                }

            }
        }

    }

    function frm_add_project_chk() {
        var err_msg = "";

        if (document.getElementById("project_title").value == "") {
            err_msg = "Please Enter the Project Title";
        }

        if (document.getElementById("owner_id").value == "") {
            err_msg = "Please Select Owner of the project.";
        }
        if (document.getElementById("dept_id").value == "") {
            err_msg = "Please Select the Department.";
        }

        var inst = FCKeditorAPI.GetInstance("txtdescription");
        var desctext = inst.GetHTML();
        document.getElementById("project_desc").value = desctext;
        if (err_msg == "") {
            return true;
        } else {
            alert(err_msg);
            return false;
        }

    }

    function frmedit_project_chk() {
        var err_msg = "";

        if (document.getElementById("project_title").value == "") {
            err_msg = "Please Enter the Project Title";
        }

        if (document.getElementById("owner_id").value == "") {
            err_msg = "Please Select Owner of the project.";
        }

        if (document.getElementById("dept_id").value == "") {
            err_msg = "Please Select the Department.";
        }

        var inst = FCKeditorAPI.GetInstance("txtdescription");
        var desctext = inst.GetHTML();
        document.getElementById("project_desc").value = desctext;
        if (err_msg == "") {
            return true;
        } else {
            alert(err_msg);
            return false;
        }

    }

    function view_project_edit(prjid) {
        document.getElementById("fade").style.display = 'block';
        document.getElementById("light").style.display = 'block';
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

        xhr.open("POST", "manage_project_edit_frm.php?id=" + prjid, true);
        xhr.send();
    }

    function openMbox(e) {
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

        xhr.open("POST", "manage_project_add_frm.php", true);
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
        STYLE="z-index:99;position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
    </div>

    <?php include("inc/header.php"); ?>
    <div class="container">
        <div class="main_data_css">
            <!-- <div class="sub_menu_div">
			<div class="sec_menu_wrapper">&emsp;
				<a href="" class="sec_menu">Task</a>
				<a href="" class="sec_menu">Department</a>
				<a href="" class="sec_menu">Projects</a>
				<a href="" class="sec_menu">Issues</a>
			</div>
		</div> -->

            <div class="dashboard_heading" style="float: left;">
                <div style="float: left;">Projects</div>
                &nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">This report is where projects are managed.</span>
                </div>
                <div class="tooltip" style="float:right;">
                    <button class="btn_ttext" onclick="openMbox('addProject_div')">Add Project</button>

                </div>
                <div style="height: 13px;">&nbsp;</div>
            </div>
            <br />

            <div style="float: left;">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmproject_srch"
                    id="frmproject_srch">

                    <span class="search_header">Entered By:</span>
                    <select id="sel_enterby" name="sel_enterby">
                        <option value="" <?php echo (($_REQUEST["sel_enterby"] == "") ? 'selected' : ''); ?>>All
                        </option>
                        <?php

                        db();
                        $query = db_query("SELECT * FROM loop_employees where status = 'Active' order by name");
                        while ($rowsel_getdata = array_shift($query)) {
                            $tmp_str = "";
                            if (isset($_REQUEST["sel_enterby"])) {
                                if ($_REQUEST["sel_enterby"] == $rowsel_getdata["b2b_id"]) {
                                    $tmp_str = " selected ";
                                }
                            } else {
                                if (isset($company_id) == $rowsel_getdata["b2b_id"]) {
                                    $tmp_str = " selected ";
                                }
                            }
                        ?>
                        <option value="<?php echo $rowsel_getdata["b2b_id"]; ?>" <?php echo $tmp_str; ?>>
                            <?php echo $rowsel_getdata['name']; ?></option>
                        <?php }
                        ?>
                    </select>
                    &nbsp;&nbsp;
                    <span class="search_header">Project Owner:</span>
                    <select id="sel_project_owner" name="sel_project_owner">
                        <option value="">All</option>
                        <?php

                        db();
                        $query = db_query("SELECT * FROM loop_employees where status = 'Active' order by name");
                        while ($rowsel_getdata = array_shift($query)) {
                            $tmp_str = "";
                            if (isset($_REQUEST["sel_project_owner"])) {
                                if ($_REQUEST["sel_project_owner"] == $rowsel_getdata["b2b_id"]) {
                                    $tmp_str = " selected ";
                                }
                            } else {
                                if ($_REQUEST["sel_project_owner"] == "") {
                                    $tmp_str = " ";
                                } elseif (isset($company_id) == $rowsel_getdata["b2b_id"]) {
                                    $tmp_str = " selected ";
                                }
                            }
                        ?>
                        <option value="<?php echo $rowsel_getdata["b2b_id"]; ?>" <?php echo $tmp_str; ?>>
                            <?php echo $rowsel_getdata['name']; ?></option>
                        <?php }
                        ?>
                    </select>

                    &nbsp;&nbsp;
                    <span class="search_header">Department:</span>
                    <select id="sel_project_dept" name="sel_project_dept">
                        <option value="" <?php echo (($_REQUEST["sel_project_dept"] == "") ? 'selected' : ''); ?>>All
                        </option>
                        <?php

                        db();
                        $query = db_query("SELECT * FROM project_dept_master order by dept_order");
                        while ($rowsel_getdata = array_shift($query)) {
                            $tmp_str = "";
                            if (isset($_REQUEST["sel_project_dept"])) {
                                if ($_REQUEST["sel_project_dept"] == $rowsel_getdata["id"]) {
                                    $tmp_str = " selected ";
                                }
                            }
                        ?>
                        <option value="<?php echo $rowsel_getdata["id"]; ?>" <?php echo $tmp_str; ?>>
                            <?php echo $rowsel_getdata['dept_name']; ?></option>
                        <?php }
                        ?>
                    </select>

                    &nbsp;&nbsp;
                    <span class="search_header">Priority:</span>
                    <select id="sel_project_priority" name="sel_project_priority">
                        <option value="">All</option>
                        <?php

                        db();
                        $query = db_query("SELECT * FROM project_priority_master order by unqid");
                        while ($rowsel_getdata = array_shift($query)) {
                            $tmp_str = "";
                            if (isset($_REQUEST["sel_project_priority"])) {
                                if ($_REQUEST["sel_project_priority"] == $rowsel_getdata["unqid"]) {
                                    $tmp_str = " selected ";
                                }
                            }
                        ?>
                        <option value="<?php echo $rowsel_getdata["unqid"]; ?>" <?php echo $tmp_str; ?>>
                            <?php echo $rowsel_getdata['priority_value']; ?></option>
                        <?php }
                        ?>
                    </select>
                    &nbsp;&nbsp;

                    <input class="btn_ttext" type="submit" id="btnsubmit" name="btnsubmit" value="Search" />
                </form>
            </div>

            <div>&nbsp;</div>
            <br>


            <div class="">
                <?php
                if ($_REQUEST["btnsubmit"]) {
                    if ($_REQUEST["sel_project_priority"] != "") {
                        db();
                        $sql_main = db_query("SELECT * FROM project_priority_master where unqid = '" . $_REQUEST["sel_project_priority"] . "' order by unqid");
                    } else {
                        db();
                        $sql_main = db_query("SELECT * FROM project_priority_master order by unqid");
                    }
                } else {
                    db();
                    $sql_main = db_query("SELECT * FROM project_priority_master order by unqid");
                }
                while ($main_row = array_shift($sql_main)) {
                ?>
                <table class="tbl_project" cellSpacing="1" cellPadding="1" border="0">
                    <tr>
                        <th colspan="9" class="headrow">
                            <?php echo $main_row["priority_value"]; ?> <a href="">Expand all Tasks</a>
                        </th>
                    </tr>
                    <tr>
                        <th class="headrow">String #</th>
                        <th class="headrow">Project Name</th>
                        <th class="headrow">Description</th>
                        <th class="headrow">Owner</th>
                        <th class="headrow">Status</th>
                        <th class="headrow">Department</th>
                        <th class="headrow">Date Entered</th>
                        <th class="headrow">Deadline</th>
                        <th class="headrow">Action</th>
                    </tr>

                    <?php
                        $srno = 1;
                        if ($_REQUEST["btnsubmit"]) {
                            if ($_REQUEST["sel_enterby"] != "" || $_REQUEST["sel_project_owner"] != "" || $_REQUEST["sel_project_dept"] != "") {
                                $sel_enterby_str = "";
                                if ($_REQUEST["sel_enterby"] != "") {
                                    $sel_enterby_str = " and project_enter_by = '" . $_REQUEST["sel_enterby"] . "'";
                                }

                                $sel_project_owner_str = "";
                                if ($_REQUEST["sel_project_owner"] != "") {
                                    $sel_project_owner_str = " and project_owner = '" . $_REQUEST["sel_project_owner"] . "'";
                                }

                                $sel_project_dept_str = "";
                                if ($_REQUEST["sel_project_dept"] != "") {
                                    $sel_project_dept_str = " and project_dept_id = '" . $_REQUEST["sel_project_dept"] . "'";
                                }

                                $qry = "SELECT * FROM project_master where project_priority_id = '" . $main_row["unqid"] . "' $sel_enterby_str $sel_project_owner_str $sel_project_dept_str order by project_id Desc";
                                //echo $qry;
                                db();
                                $sql1 = db_query($qry);
                            } else {
                                db();
                                $sql1 = db_query("SELECT * FROM project_master where project_priority_id = '" . $main_row["unqid"] . "' ORDER BY project_id DESC");
                            }
                        } else {
                            db();
                            $sql1 = db_query("SELECT * FROM project_master where project_priority_id = '" . $main_row["unqid"] . "' ORDER BY project_id DESC");
                        }

                        while ($r = array_shift($sql1)) {
                            echo "<tr class='tbl_project'>";
                            echo "<td class=''>" . $srno++ . "</td>";
                            echo "<td class=''>" . $r['project_name'] . "</td>";
                            echo "<td class=''>" . $r['project_description'] . "</td>";
                            echo "<td class=''>" . get_user_name($r['project_owner']) . "</td>";
                            echo "<td class=''>" . get_status_name($r['project_status_id']) . "</td>";
                            echo "<td class=''>" . get_department_name($r['project_dept_id']) . "</td>";
                            echo "<td class=''>" . $r['project_enter_on'] . "</td>";
                            echo "<td class=''>" . $r['project_deadline'] . "</td>";
                            echo "<td class=''><input type='button' value='Edit' onclick='view_project_edit(" . $r['project_id'] . ")'></td>";
                            echo "</tr>";
                        }
                        ?>
                </table>
                <br><br>
                <?php } ?>
            </div>

            <script>
            /*
function openMbox(e){
	document.getElementById(e).style.display = "block";
}

function closeMbox(e){
	document.getElementById(e).style.display = "none";
}
*/
            var modal = document.getElementById("fade");
            window.onclick = function(event) {
                if (event.target == modal) {
                    // modal.style.display = "none";
                }
            }
            </script>

</body>

</html>